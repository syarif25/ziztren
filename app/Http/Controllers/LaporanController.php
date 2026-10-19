<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanBeasiswa;
use App\Models\MasterPeriode;
use App\Models\Lembaga;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman Laporan & Rekapitulasi Beasiswa
     */
    public function index()
    {
        $periodes = MasterPeriode::orderBy('tahun_masehi', 'desc')->orderBy('gelombang', 'desc')->get();
        $lembagas = Lembaga::orderBy('nama_lembaga', 'asc')->get();

        return view('laporan.index', compact('periodes', 'lembagas'));
    }

    /**
     * Membangun query dengan filter terpusat
     */
    private function buildFilterQuery(Request $request)
    {
        $query = PengajuanBeasiswa::with(['santri', 'periode', 'lembaga1', 'lembaga2'])
            ->select('pengajuan_beasiswas.*');

        if ($request->filled('periode_id')) {
            $query->where('periode_id', $request->periode_id);
        }

        if ($request->filled('status_pengajuan')) {
            $query->where('status_pengajuan', $request->status_pengajuan);
        }

        if ($request->filled('lembaga_id')) {
            $lembagaId = $request->lembaga_id;
            $query->where(function ($q) use ($lembagaId) {
                $q->where('lembaga_1_id', $lembagaId)
                  ->orWhere('lembaga_2_id', $lembagaId)
                  ->orWhere('lembaga_qiroati_id', $lembagaId);
            });
        }

        if ($request->filled('tanggal_awal')) {
            $query->whereDate('created_at', '>=', $request->tanggal_awal);
        }

        if ($request->filled('tanggal_akhir')) {
            $query->whereDate('created_at', '<=', $request->tanggal_akhir);
        }

        return $query;
    }

    /**
     * DataTables Server-side
     */
    public function data(Request $request)
    {
        $query = $this->buildFilterQuery($request);

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('santri_nama', function ($row) {
                return $row->santri ? $row->santri->nama_lengkap : '-';
            })
            ->addColumn('lembaga_nama', function ($row) {
                $lembagas = [];
                if ($row->lembaga1) $lembagas[] = $row->lembaga1->singkatan ?: $row->lembaga1->nama_lembaga;
                if ($row->lembaga2) $lembagas[] = $row->lembaga2->singkatan ?: $row->lembaga2->nama_lembaga;
                return count($lembagas) > 0 ? implode(' / ', $lembagas) : '-';
            })
            ->addColumn('periode_text', function ($row) {
                return $row->periode ? 'Gel ' . $row->periode->gelombang . ' (' . $row->periode->tahun_masehi . ')' : '-';
            })
            ->editColumn('jumlah_utap', function ($row) {
                return 'Rp ' . number_format($row->jumlah_utap, 0, ',', '.');
            })
            ->addColumn('total_spp', function ($row) {
                $spp = ($row->spp_gasal ?? 0) + ($row->spp_genap ?? 0);
                return 'Rp ' . number_format($spp, 0, ',', '.');
            })
            ->editColumn('living_cost', function ($row) {
                return 'Rp ' . number_format($row->living_cost, 0, ',', '.');
            })
            ->editColumn('biaya_lain', function ($row) {
                return 'Rp ' . number_format($row->biaya_lain, 0, ',', '.');
            })
            ->addColumn('grand_total', function ($row) {
                $total = ($row->jumlah_utap ?? 0) + ($row->spp_gasal ?? 0) + ($row->spp_genap ?? 0) + ($row->living_cost ?? 0) + ($row->biaya_lain ?? 0);
                return '<strong>Rp ' . number_format($total, 0, ',', '.') . '</strong>';
            })
            ->editColumn('status_pengajuan', function ($row) {
                $badges = [
                    'draft' => '<span class="badge bg-secondary">Draft</span>',
                    'proses' => '<span class="badge bg-info">Proses</span>',
                    'acc' => '<span class="badge bg-primary">ACC</span>',
                    'pencairan' => '<span class="badge bg-success">Pencairan</span>',
                ];
                return $badges[strtolower($row->status_pengajuan)] ?? '<span class="badge bg-dark">' . ucfirst($row->status_pengajuan) . '</span>';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? Carbon::parse($row->created_at)->translatedFormat('d M Y') : '-';
            })
            ->editColumn('tanggal_bantuan', function ($row) {
                return $row->tanggal_bantuan ? Carbon::parse($row->tanggal_bantuan)->translatedFormat('d M Y') : '-';
            })
            ->rawColumns(['grand_total', 'status_pengajuan'])
            ->make(true);
    }

    /**
     * Mengembalikan ringkasan statistik (KPI cards) sesuai filter
     */
    public function summary(Request $request)
    {
        $query = $this->buildFilterQuery($request);

        $totalSantri = (clone $query)->count();
        $totalUtap   = (clone $query)->sum('jumlah_utap') ?: 0;
        $totalSppGasal = (clone $query)->sum('spp_gasal') ?: 0;
        $totalSppGenap = (clone $query)->sum('spp_genap') ?: 0;
        $totalSpp    = $totalSppGasal + $totalSppGenap;
        $totalLiving = (clone $query)->sum('living_cost') ?: 0;
        $totalLain   = (clone $query)->sum('biaya_lain') ?: 0;
        $grandTotal  = $totalUtap + $totalSpp + $totalLiving + $totalLain;

        // Count per status
        $countDraft     = (clone $query)->where('status_pengajuan', 'draft')->count();
        $countProses    = (clone $query)->where('status_pengajuan', 'proses')->count();
        $countAcc       = (clone $query)->where('status_pengajuan', 'acc')->count();
        $countPencairan = (clone $query)->where('status_pengajuan', 'pencairan')->count();

        return response()->json([
            'total_santri'      => $totalSantri,
            'total_utap'        => 'Rp ' . number_format($totalUtap, 0, ',', '.'),
            'total_spp'         => 'Rp ' . number_format($totalSpp, 0, ',', '.'),
            'total_living'      => 'Rp ' . number_format($totalLiving, 0, ',', '.'),
            'total_lain'        => 'Rp ' . number_format($totalLain, 0, ',', '.'),
            'grand_total'       => 'Rp ' . number_format($grandTotal, 0, ',', '.'),
            'count_draft'       => $countDraft,
            'count_proses'      => $countProses,
            'count_acc'         => $countAcc,
            'count_pencairan'   => $countPencairan,
        ]);
    }

    /**
     * Ekspor Laporan ke Spreadsheet / Excel (.csv)
     */
    public function exportExcel(Request $request)
    {
        $query = $this->buildFilterQuery($request)->orderBy('created_at', 'asc')->get();

        $filename = 'Laporan_Rekapitulasi_Beasiswa_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($query) {
            $file = fopen('php://output', 'w');

            // Tambahkan UTF-8 BOM agar Excel membukanya dengan encoding yang benar
            fputs($file, "\xEF\xBB\xBF");

            // Header kolom
            fputcsv($file, [
                'No',
                'Tgl Pengajuan',
                'NIS',
                'Nama Santri',
                'Jenis Kelamin',
                'Asrama/Kamar',
                'Nama Wali',
                'No HP Wali',
                'Lembaga/Madrasah',
                'Sekolah/PT',
                'Periode / Gelombang',
                'UTAP (Rp)',
                'SPP Gasal (Rp)',
                'SPP Genap (Rp)',
                'Living Cost (Rp)',
                'Biaya Lain (Rp)',
                'Total Bantuan (Rp)',
                'Status',
                'Tgl Pencairan'
            ]);

            $no = 1;
            $sumUtap = 0;
            $sumGasal = 0;
            $sumGenap = 0;
            $sumLiving = 0;
            $sumLain = 0;
            $sumTotal = 0;

            foreach ($query as $row) {
                $totalBantuan = ($row->jumlah_utap ?? 0) + ($row->spp_gasal ?? 0) + ($row->spp_genap ?? 0) + ($row->living_cost ?? 0) + ($row->biaya_lain ?? 0);

                $sumUtap   += ($row->jumlah_utap ?? 0);
                $sumGasal  += ($row->spp_gasal ?? 0);
                $sumGenap  += ($row->spp_genap ?? 0);
                $sumLiving += ($row->living_cost ?? 0);
                $sumLain   += ($row->biaya_lain ?? 0);
                $sumTotal  += $totalBantuan;

                fputcsv($file, [
                    $no++,
                    $row->created_at ? Carbon::parse($row->created_at)->format('Y-m-d') : '-',
                    "'" . $row->nis, // Prefix kutip agar tidak terpotong sebagai float oleh Excel
                    $row->santri->nama_lengkap ?? '-',
                    ($row->santri->jenis_kelamin ?? '') == 'L' ? 'Laki-laki' : (($row->santri->jenis_kelamin ?? '') == 'P' ? 'Perempuan' : '-'),
                    $row->santri->kamar ?? '-',
                    $row->santri->nama_wali ?? '-',
                    $row->santri->no_hp_wali ? "'" . $row->santri->no_hp_wali : '-',
                    $row->lembaga1->nama_lembaga ?? '-',
                    $row->lembaga2->nama_lembaga ?? '-',
                    $row->periode ? 'Gel ' . $row->periode->gelombang . ' - ' . $row->periode->tahun_masehi : '-',
                    $row->jumlah_utap ?: 0,
                    $row->spp_gasal ?: 0,
                    $row->spp_genap ?: 0,
                    $row->living_cost ?: 0,
                    $row->biaya_lain ?: 0,
                    $totalBantuan,
                    ucfirst($row->status_pengajuan),
                    $row->tanggal_bantuan ? Carbon::parse($row->tanggal_bantuan)->format('Y-m-d') : '-'
                ]);
            }

            // Baris Akumulasi / Total
            fputcsv($file, [
                'TOTAL',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                $sumUtap,
                $sumGasal,
                $sumGenap,
                $sumLiving,
                $sumLain,
                $sumTotal,
                '',
                ''
            ]);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Cetak Rekapitulasi Laporan dalam format PDF (Landscape)
     */
    public function cetakPdf(Request $request)
    {
        $pengajuans = $this->buildFilterQuery($request)->orderBy('created_at', 'asc')->get();

        $filterInfo = [
            'periode'   => $request->filled('periode_id') ? MasterPeriode::find($request->periode_id)?->nama_periode : 'Semua Periode',
            'status'    => $request->filled('status_pengajuan') ? ucfirst($request->status_pengajuan) : 'Semua Status',
            'lembaga'   => $request->filled('lembaga_id') ? Lembaga::find($request->lembaga_id)?->nama_lembaga : 'Semua Lembaga',
            'tgl_awal'  => $request->tanggal_awal ? Carbon::parse($request->tanggal_awal)->translatedFormat('d M Y') : null,
            'tgl_akhir' => $request->tanggal_akhir ? Carbon::parse($request->tanggal_akhir)->translatedFormat('d M Y') : null,
        ];

        // Total hitungan
        $totals = [
            'santri'   => $pengajuans->count(),
            'utap'     => $pengajuans->sum('jumlah_utap') ?: 0,
            'spp'      => $pengajuans->sum(fn ($r) => ($r->spp_gasal ?? 0) + ($r->spp_genap ?? 0)),
            'living'   => $pengajuans->sum('living_cost') ?: 0,
            'lain'     => $pengajuans->sum('biaya_lain') ?: 0,
        ];
        $totals['grand'] = $totals['utap'] + $totals['spp'] + $totals['living'] + $totals['lain'];

        $pdf = Pdf::loadView('laporan.pdf', compact('pengajuans', 'filterInfo', 'totals'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('Rekapitulasi_Beasiswa_' . date('Ymd_His') . '.pdf');
    }
}
