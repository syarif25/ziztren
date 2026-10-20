<?php

namespace App\Http\Controllers;

use App\Models\MasterPeriode;
use App\Models\PencairanBeasiswa;
use App\Models\PengajuanBeasiswa;
use App\Models\HistoriStatusBeasiswa;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class PencairanController extends Controller
{
    /**
     * Halaman Utama Menu Pencairan & Pencairan Dana
     */
    public function index()
    {
        // Statistik ringkas pencairan (scoped per gender user jika operator)
        $totalPengajuanAcc = PengajuanBeasiswa::forUser()->whereIn('status_pengajuan', ['acc', 'pencairan'])->count();
        
        $totalKomitmenDana = PengajuanBeasiswa::forUser()->whereIn('status_pengajuan', ['acc', 'pencairan'])
            ->selectRaw('SUM(COALESCE(jumlah_utap,0) + COALESCE(spp_gasal,0) + COALESCE(spp_genap,0) + COALESCE(living_cost,0) + COALESCE(biaya_lain,0)) as total')
            ->value('total') ?? 0;

        $scopedPengajuanIds = PengajuanBeasiswa::forUser()->whereIn('status_pengajuan', ['acc', 'pencairan'])->pluck('id');

        $totalTerealisasi = PencairanBeasiswa::whereIn('pengajuan_id', $scopedPengajuanIds)->sum('nominal');
        $sisaBelumCair = max(0, $totalKomitmenDana - $totalTerealisasi);

        // Rekap per metode
        $realisasiTransfer = PencairanBeasiswa::whereIn('pengajuan_id', $scopedPengajuanIds)->where('metode_pencairan', 'transfer')->sum('nominal');
        $realisasiTunai = PencairanBeasiswa::whereIn('pengajuan_id', $scopedPengajuanIds)->where('metode_pencairan', 'tunai')->sum('nominal');

        $periodes = MasterPeriode::orderBy('tahun_masehi', 'desc')->orderBy('gelombang', 'asc')->get();

        return view('pencairan.index', compact(
            'totalPengajuanAcc',
            'totalKomitmenDana',
            'totalTerealisasi',
            'sisaBelumCair',
            'realisasiTransfer',
            'realisasiTunai',
            'periodes'
        ));
    }

    /**
     * DataTable: Daftar Santri Siap / Sedang Dicairkan
     */
    public function data(Request $request)
    {
        $query = PengajuanBeasiswa::forUser()
            ->with(['santri', 'periode', 'pencairans'])
            ->whereIn('status_pengajuan', ['acc', 'pencairan'])
            ->latest('updated_at');

        // Filter per kolom: Santri (Nama / NIS / Kamar)
        if ($request->filled('filter_santri')) {
            $kw = $request->filter_santri;
            $query->where(function ($q) use ($kw) {
                $q->where('nis', 'like', "%{$kw}%")
                  ->orWhereHas('santri', function ($sq) use ($kw) {
                      $sq->where('nama_lengkap', 'like', "%{$kw}%")
                         ->orWhere('kamar', 'like', "%{$kw}%");
                  });
            });
        }

        // Filter per kolom: Periode
        if ($request->filled('filter_periode')) {
            $query->where('periode_id', $request->filter_periode);
        }

        // Filter per kolom: Status Pencairan (siap, sebagian, tuntas)
        if ($request->filled('filter_status')) {
            $st = $request->filter_status;
            if ($st === 'siap') {
                $query->whereDoesntHave('pencairans');
            } elseif ($st === 'sebagian') {
                $query->whereHas('pencairans')->whereRaw('(SELECT COALESCE(SUM(nominal),0) FROM pencairan_beasiswas WHERE pengajuan_id = pengajuan_beasiswas.id) < (COALESCE(jumlah_utap,0) + COALESCE(spp_gasal,0) + COALESCE(spp_genap,0) + COALESCE(living_cost,0) + COALESCE(biaya_lain,0))');
            } elseif ($st === 'tuntas') {
                $query->whereHas('pencairans')->whereRaw('(SELECT COALESCE(SUM(nominal),0) FROM pencairan_beasiswas WHERE pengajuan_id = pengajuan_beasiswas.id) >= (COALESCE(jumlah_utap,0) + COALESCE(spp_gasal,0) + COALESCE(spp_genap,0) + COALESCE(living_cost,0) + COALESCE(biaya_lain,0))');
            }
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('santri_info', function ($item) {
                $nama = e($item->santri?->nama_lengkap ?? '-');
                $nis = e($item->nis ?? '-');
                $asrama = e($item->santri?->asrama ? $item->santri->asrama . ($item->santri->kamar ? ' / Kamar ' . $item->santri->kamar : '') : '-');
                return "<div><strong class='text-dark'>{$nama}</strong><br><small class='text-muted'>NIS: {$nis}</small><br><small class='text-muted'>{$asrama}</small></div>";
            })
            ->addColumn('periode_info', function ($item) {
                return $item->periode?->nama_periode ?? '-';
            })
            ->addColumn('hak_bantuan', function ($item) {
                return 'Rp ' . number_format($item->total_bantuan, 0, ',', '.');
            })
            ->addColumn('tercairkan', function ($item) {
                $cair = $item->total_dicairkan;
                $persen = $item->progress_pencairan;
                $color = $persen == 100 ? 'bg-success' : ($persen > 0 ? 'bg-info' : 'bg-secondary');
                return "<div><strong class='text-success'>Rp " . number_format($cair, 0, ',', '.') . "</strong>
                        <div class='progress mt-1' style='height: 5px;'>
                            <div class='progress-bar {$color}' style='width: {$persen}%;'></div>
                        </div>
                        <small class='text-muted'>{$persen}% tersalurkan</small></div>";
            })
            ->addColumn('sisa_dana', function ($item) {
                $sisa = $item->sisa_pencairan;
                $cls = $sisa == 0 ? 'text-muted' : 'text-danger fw-bold';
                return "<span class='{$cls}'>Rp " . number_format($sisa, 0, ',', '.') . "</span>";
            })
            ->addColumn('status_badge', function ($item) {
                $persen = $item->progress_pencairan;
                if ($persen >= 100) {
                    return '<span class="badge bg-success font-size-11"><i class="ri-checkbox-circle-line me-1"></i>Tuntas Dicairkan</span>';
                } elseif ($persen > 0) {
                    return '<span class="badge bg-warning text-dark font-size-11"><i class="ri-time-line me-1"></i>Pencairan Sebagian</span>';
                } else {
                    return '<span class="badge bg-primary font-size-11"><i class="ri-check-line me-1"></i>Siap Dicairkan</span>';
                }
            })
            ->addColumn('action', function ($item) {
                $btnCatat = '<button type="button" class="btn btn-sm btn-success btn-catat" data-id="' . $item->id . '" title="Catat Pencairan"><i class="ri-hand-coin-line me-1"></i>Pencairan</button>';
                $btnRiwayat = '<a href="' . route('pencairan.show', $item->id) . '" class="btn btn-sm btn-outline-info" title="Lihat Buku Pencairan"><i class="ri-file-list-line"></i></a>';
                return '<div class="d-flex gap-1">' . $btnCatat . ' ' . $btnRiwayat . '</div>';
            })
            ->rawColumns(['santri_info', 'tercairkan', 'sisa_dana', 'status_badge', 'action'])
            ->make(true);
    }

    /**
     * Detail Buku Riwayat Pencairan per Santri
     */
    public function show($id)
    {
        $pengajuan = PengajuanBeasiswa::forUser()->with(['santri', 'periode', 'lembaga1', 'pencairans.user'])->findOrFail($id);

        // Hitung realisasi per komponen
        $rincian = [
            'utap' => [
                'nama' => 'UTAP (Pondok)',
                'hak' => (float) $pengajuan->jumlah_utap,
                'cair' => (float) $pengajuan->pencairans->where('kategori_bantuan', 'utap')->sum('nominal'),
                'metode_default' => 'transfer',
            ],
            'spp_gasal' => [
                'nama' => 'SPP Gasal',
                'hak' => (float) $pengajuan->spp_gasal,
                'cair' => (float) $pengajuan->pencairans->where('kategori_bantuan', 'spp_gasal')->sum('nominal'),
                'metode_default' => 'transfer',
            ],
            'spp_genap' => [
                'nama' => 'SPP Genap',
                'hak' => (float) $pengajuan->spp_genap,
                'cair' => (float) $pengajuan->pencairans->where('kategori_bantuan', 'spp_genap')->sum('nominal'),
                'metode_default' => 'transfer',
            ],
            'living_cost' => [
                'nama' => 'Living Cost (Uang Saku/Makan)',
                'hak' => (float) $pengajuan->living_cost,
                'cair' => (float) $pengajuan->pencairans->where('kategori_bantuan', 'living_cost')->sum('nominal'),
                'metode_default' => 'tunai',
            ],
            'biaya_lain' => [
                'nama' => 'Biaya Lain-lain',
                'hak' => (float) $pengajuan->biaya_lain,
                'cair' => (float) $pengajuan->pencairans->where('kategori_bantuan', 'biaya_lain')->sum('nominal'),
                'metode_default' => 'tunai',
            ],
        ];

        return view('pencairan.show', compact('pengajuan', 'rincian'));
    }

    /**
     * Ambil Data JSON untuk Modal Pencairan Cepat
     */
    public function getInfo($id)
    {
        $pengajuan = PengajuanBeasiswa::forUser()->with(['santri', 'periode', 'lembaga1', 'pencairans'])->findOrFail($id);

        $komponen = [
            [
                'key' => 'living_cost',
                'label' => 'Living Cost (Uang Saku/Makan)',
                'hak' => (float) $pengajuan->living_cost,
                'cair' => (float) $pengajuan->pencairans->where('kategori_bantuan', 'living_cost')->sum('nominal'),
                'sisa' => max(0, (float)$pengajuan->living_cost - (float)$pengajuan->pencairans->where('kategori_bantuan', 'living_cost')->sum('nominal')),
                'metode' => 'tunai'
            ],
            [
                'key' => 'utap',
                'label' => 'UTAP (Uang Tahunan Pesantren)',
                'hak' => (float) $pengajuan->jumlah_utap,
                'cair' => (float) $pengajuan->pencairans->where('kategori_bantuan', 'utap')->sum('nominal'),
                'sisa' => max(0, (float)$pengajuan->jumlah_utap - (float)$pengajuan->pencairans->where('kategori_bantuan', 'utap')->sum('nominal')),
                'metode' => 'transfer'
            ],
            [
                'key' => 'spp_gasal',
                'label' => 'SPP Gasal',
                'hak' => (float) $pengajuan->spp_gasal,
                'cair' => (float) $pengajuan->pencairans->where('kategori_bantuan', 'spp_gasal')->sum('nominal'),
                'sisa' => max(0, (float)$pengajuan->spp_gasal - (float)$pengajuan->pencairans->where('kategori_bantuan', 'spp_gasal')->sum('nominal')),
                'metode' => 'transfer'
            ],
            [
                'key' => 'spp_genap',
                'label' => 'SPP Genap',
                'hak' => (float) $pengajuan->spp_genap,
                'cair' => (float) $pengajuan->pencairans->where('kategori_bantuan', 'spp_genap')->sum('nominal'),
                'sisa' => max(0, (float)$pengajuan->spp_genap - (float)$pengajuan->pencairans->where('kategori_bantuan', 'spp_genap')->sum('nominal')),
                'metode' => 'transfer'
            ],
            [
                'key' => 'biaya_lain',
                'label' => 'Biaya Lain-lain',
                'hak' => (float) $pengajuan->biaya_lain,
                'cair' => (float) $pengajuan->pencairans->where('kategori_bantuan', 'biaya_lain')->sum('nominal'),
                'sisa' => max(0, (float)$pengajuan->biaya_lain - (float)$pengajuan->pencairans->where('kategori_bantuan', 'biaya_lain')->sum('nominal')),
                'metode' => 'tunai'
            ],
        ];

        // Hanya tampilkan komponen yang memang diajukan (hak > 0) atau sudah memiliki riwayat pencairan
        $komponen = array_values(array_filter($komponen, function ($item) {
            return $item['hak'] > 0 || $item['cair'] > 0;
        }));

        return response()->json([
            'pengajuan' => [
                'id' => $pengajuan->id,
                'nis' => $pengajuan->nis,
                'nama' => $pengajuan->santri?->nama_lengkap ?? '-',
                'kamar' => $pengajuan->santri?->kamar ? 'Kamar ' . $pengajuan->santri->kamar : '-',
                'wali' => $pengajuan->santri?->nama_wali ? $pengajuan->santri->nama_wali . ($pengajuan->santri->no_hp_wali ? ' (' . $pengajuan->santri->no_hp_wali . ')' : '') : '-',
                'lembaga' => $pengajuan->lembaga1?->nama_lembaga ?? '-',
                'periode' => $pengajuan->periode?->nama_periode ?? '-',
                'total_bantuan' => $pengajuan->total_bantuan,
                'total_dicairkan' => $pengajuan->total_dicairkan,
                'sisa_pencairan' => $pengajuan->sisa_pencairan,
            ],
            'komponen' => $komponen
        ]);
    }

    /**
     * Simpan Transaksi Pencairan (Bertahap / Parsial / Transfer / Tunai)
     */
    public function store(Request $request)
    {
        $request->validate([
            'pengajuan_id' => 'required|exists:pengajuan_beasiswas,id',
            'kategori_bantuan' => 'required|in:utap,spp_gasal,spp_genap,living_cost,biaya_lain',
            'nominal' => 'required|numeric|min:1',
            'metode_pencairan' => 'required|in:transfer,tunai',
            'tanggal_cair' => 'required|date',
            'tahap_ke' => 'nullable|string|max:50',
            'nomor_referensi' => 'nullable|string|max:100',
            'nama_penerima' => 'nullable|string|max:150',
            'file_bukti' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'catatan' => 'nullable|string|max:500',
        ]);

        $pengajuan = PengajuanBeasiswa::forUser()->findOrFail($request->pengajuan_id);

        // Cek batasan hak bantuan untuk kategori terkait
        $fieldMap = [
            'utap' => 'jumlah_utap',
            'spp_gasal' => 'spp_gasal',
            'spp_genap' => 'spp_genap',
            'living_cost' => 'living_cost',
            'biaya_lain' => 'biaya_lain',
        ];
        $targetField = $fieldMap[$request->kategori_bantuan];
        $hakKategori = (float) ($pengajuan->$targetField ?? 0);
        $sudahCairKategori = (float) PencairanBeasiswa::where('pengajuan_id', $pengajuan->id)
            ->where('kategori_bantuan', $request->kategori_bantuan)
            ->sum('nominal');

        $sisaKategori = max(0, $hakKategori - $sudahCairKategori);

        if ($request->nominal > $sisaKategori) {
            return response()->json([
                'success' => false,
                'message' => 'Nominal pencairan (Rp ' . number_format($request->nominal, 0, ',', '.') . ') melebihi sisa plafon kategori ini (Rp ' . number_format($sisaKategori, 0, ',', '.') . ').'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $filePath = null;
            if ($request->hasFile('file_bukti')) {
                $filePath = $request->file('file_bukti')->store('bukti_pencairan', 'public');
            }

            $pencairan = PencairanBeasiswa::create([
                'pengajuan_id' => $pengajuan->id,
                'kategori_bantuan' => $request->kategori_bantuan,
                'nominal' => $request->nominal,
                'metode_pencairan' => $request->metode_pencairan,
                'tanggal_cair' => $request->tanggal_cair,
                'tahap_ke' => $request->tahap_ke,
                'nomor_referensi' => $request->nomor_referensi,
                'nama_penerima' => $request->nama_penerima ?: ($pengajuan->santri?->nama_lengkap ?? '-'),
                'file_bukti' => $filePath,
                'catatan' => $request->catatan,
                'created_by' => auth()->id(),
            ]);

            // Jika status pengajuan masih 'ACC', update menjadi 'pencairan'
            if (strtolower($pengajuan->status_pengajuan) === 'acc') {
                $pengajuan->update(['status_pengajuan' => 'pencairan']);

                HistoriStatusBeasiswa::create([
                    'pengajuan_id' => $pengajuan->id,
                    'status' => 'pencairan',
                    'keterangan' => 'Pencairan beasiswa tahap pertama dicatat (' . $pencairan->kategori_label . ': Rp ' . number_format($pencairan->nominal, 0, ',', '.') . ')',
                    'user_id' => auth()->id(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pencairan ' . $pencairan->kategori_label . ' sebesar Rp ' . number_format($pencairan->nominal, 0, ',', '.') . ' berhasil dicatat!',
                'pencairan_id' => $pencairan->id,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mencatat pencairan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hapus Catatan Pencairan
     */
    public function destroy($id)
    {
        $pencairan = PencairanBeasiswa::whereHas('pengajuan', function ($q) {
            $q->forUser();
        })->findOrFail($id);

        $pengajuanId = $pencairan->pengajuan_id;

        if ($pencairan->file_bukti && Storage::disk('public')->exists($pencairan->file_bukti)) {
            Storage::disk('public')->delete($pencairan->file_bukti);
        }

        $pencairan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data transaksi pencairan berhasil dihapus.'
        ]);
    }

    /**
     * Cetak Kuitansi Tanda Terima Resmi (PDF A5 / Kuitansi)
     */
    public function cetakKuitansi($id)
    {
        $pencairan = PencairanBeasiswa::whereHas('pengajuan', function ($q) {
            $q->forUser();
        })->with(['pengajuan.santri', 'pengajuan.periode', 'user'])->findOrFail($id);
        
        $pdf = Pdf::loadView('pencairan.kuitansi', compact('pencairan'))
            ->setPaper('a5', 'landscape');

        return $pdf->stream('Kuitansi_Pencairan_' . ($pencairan->pengajuan->santri->nis ?? 'beasiswa') . '_' . $pencairan->id . '.pdf');
    }

    /**
     * Cetak Lembar Rekapitulasi & Riwayat Pencairan Beasiswa per Santri (1 Lembar PDF)
     */
    public function cetakRiwayatPdf($id)
    {
        $pengajuan = PengajuanBeasiswa::forUser()->with(['santri', 'periode', 'lembaga1', 'pencairans.user'])->findOrFail($id);

        $pdf = Pdf::loadView('pencairan.riwayat_pdf', compact('pengajuan'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Lembar_Riwayat_Pencairan_' . ($pengajuan->santri->nis ?? 'santri') . '.pdf');
    }
}
