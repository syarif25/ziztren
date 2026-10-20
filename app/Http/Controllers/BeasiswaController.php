<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanBeasiswa;
use App\Models\DokumenPengajuan;
use App\Models\MasterPeriode;
use App\Models\Santri;
use App\Models\Lembaga;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BeasiswaController extends Controller
{
    public function index()
    {
        return view('beasiswa');
    }

    public function data()
    {
        $pengajuan = PengajuanBeasiswa::forUser()->with(['santri', 'lembaga1', 'lembaga2', 'periode'])->select('pengajuan_beasiswas.*');

        return DataTables::of($pengajuan)
            ->addIndexColumn()
            ->addColumn('periode', function ($row) {
                if ($row->periode) {
                    return $row->periode->tahun_masehi . ' — Gel. ' . $row->periode->gelombang;
                }
                return '-';
            })
            ->addColumn('nama_santri', function ($row) {
                return $row->santri ? $row->santri->nama_lengkap : '-';
            })
            ->addColumn('madrasah', function ($row) {
                return $row->lembaga1 ? $row->lembaga1->nama_lembaga : '-';
            })
            ->addColumn('sekolah', function ($row) {
                return $row->lembaga2 ? $row->lembaga2->nama_lembaga : '-';
            })
            ->addColumn('utap', function ($row) { return 'Rp ' . number_format($row->jumlah_utap, 0, ',', '.'); })
            ->addColumn('spp_gasal', function ($row) { return 'Rp ' . number_format($row->spp_gasal, 0, ',', '.'); })
            ->addColumn('spp_genap', function ($row) { return 'Rp ' . number_format($row->spp_genap, 0, ',', '.'); })
            ->addColumn('living_cost', function ($row) { return 'Rp ' . number_format($row->living_cost, 0, ',', '.'); })
            ->addColumn('biaya_lain', function ($row) { return 'Rp ' . number_format($row->biaya_lain, 0, ',', '.'); })
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? \Carbon\Carbon::parse($row->created_at)->translatedFormat('d M Y') : '-';
            })
            ->editColumn('tanggal_bantuan', function ($row) {
                return $row->tanggal_bantuan ? \Carbon\Carbon::parse($row->tanggal_bantuan)->translatedFormat('d M Y') : null;
            })
            ->filterColumn('created_at', function($query, $keyword) {
                $sql = "DATE_FORMAT(pengajuan_beasiswas.created_at, '%d %b %Y') like ?";
                // Handle Indonesian month abbreviation diffs if necessary, but '%d %b %Y' covers "Sep", "Nov", "Dec/Des".
                // We'll also allow standard format searching
                $query->whereRaw($sql, ["%{$keyword}%"])
                      ->orWhere('pengajuan_beasiswas.created_at', 'like', "%{$keyword}%")
                      ->orWhere('pengajuan_beasiswas.tanggal_bantuan', 'like', "%{$keyword}%");
            })
            ->filterColumn('periode', function($query, $keyword) {
                $query->whereHas('periode', function($q) use ($keyword) {
                    $q->where('tahun_masehi', 'like', "%{$keyword}%")
                      ->orWhere('gelombang', 'like', "%{$keyword}%");
                });
            })
            ->filterColumn('nama_santri', function($query, $keyword) {
                $query->whereHas('santri', function($q) use ($keyword) {
                    $q->where('nama_lengkap', 'like', "%{$keyword}%");
                })->orWhere('nis', 'like', "%{$keyword}%");
            })
            ->filterColumn('madrasah', function($query, $keyword) {
                $query->whereHas('lembaga1', function($q) use ($keyword) {
                    $q->where('nama_lembaga', 'like', "%{$keyword}%");
                })->orWhere('kelas_pendidikan_1', 'like', "%{$keyword}%");
            })
            ->filterColumn('sekolah', function($query, $keyword) {
                $query->whereHas('lembaga2', function($q) use ($keyword) {
                    $q->where('nama_lembaga', 'like', "%{$keyword}%");
                })->orWhere('kelas_pendidikan_2', 'like', "%{$keyword}%");
            })
            ->filterColumn('utap', function($query, $keyword) {
                $query->where('jumlah_utap', 'like', "%" . str_replace('.', '', $keyword) . "%");
            })
            ->filterColumn('spp_gasal', function($query, $keyword) {
                $query->where('spp_gasal', 'like', "%" . str_replace('.', '', $keyword) . "%");
            })
            ->filterColumn('spp_genap', function($query, $keyword) {
                $query->where('spp_genap', 'like', "%" . str_replace('.', '', $keyword) . "%");
            })
            ->filterColumn('living_cost', function($query, $keyword) {
                $query->where('living_cost', 'like', "%" . str_replace('.', '', $keyword) . "%");
            })
            ->filterColumn('biaya_lain', function($query, $keyword) {
                $query->where('biaya_lain', 'like', "%" . str_replace('.', '', $keyword) . "%");
            })
            ->make(true);
    }

    public function create()
    {
        $periode = MasterPeriode::where('status', 'aktif')->first();

        if (!$periode) {
            return redirect()->route('beasiswa.index')->with('error', 'Maaf, tidak ada periode pendaftaran beasiswa yang aktif saat ini.');
        }

        $now = now()->format('Y-m-d');
        if ($now < $periode->tgl_buka || $now > $periode->tgl_tutup) {
            return redirect()->route('beasiswa.index')->with('error', 'Pendaftaran gelombang ini hanya dibuka dari tanggal ' . \Carbon\Carbon::parse($periode->tgl_buka)->format('d M Y') . ' sampai ' . \Carbon\Carbon::parse($periode->tgl_tutup)->format('d M Y') . '.');
        }

        $jumlahPengajuan = PengajuanBeasiswa::where('periode_id', $periode->id)->count();
        if ($jumlahPengajuan >= $periode->kuota) {
            return redirect()->route('beasiswa.index')->with('error', 'Mohon maaf, kuota pendaftaran untuk gelombang ini sudah penuh (Batas: ' . $periode->kuota . ' pendaftar).');
        }

        $santris = Santri::orderBy('nama_lengkap', 'asc')->get();
        $lembagas = Lembaga::orderBy('nama_lembaga', 'asc')->get();
        return view('beasiswa.create', compact('santris', 'lembagas', 'periode'));
    }

    public function getSantriDetail($nis)
    {
        $santri = Santri::forUser()->where('nis', $nis)->first();
        if ($santri) {
            return response()->json($santri);
        }
        return response()->json(null, 404);
    }

    public function searchSantri(Request $request)
    {
        $search = $request->q;
        $query = Santri::forUser()->orderBy('nama_lengkap', 'asc');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $santris = $query->limit(50)->get();

        $formattedSantris = [];
        foreach ($santris as $santri) {
            $formattedSantris[] = [
                'id' => $santri->nis,
                'text' => $santri->nis . ' - ' . $santri->nama_lengkap
            ];
        }

        return response()->json($formattedSantris);
    }

    public function store(Request $request)
    {
        // Unformat nominal inputs
                $request->merge([
            'jumlah_utap' => str_replace('.', '', $request->jumlah_utap ?? 0) ?: 0,
            'spp_gasal' => str_replace('.', '', $request->spp_gasal ?? 0) ?: 0,
            'spp_genap' => str_replace('.', '', $request->spp_genap ?? 0) ?: 0,
            'living_cost' => str_replace('.', '', $request->living_cost ?? 0) ?: 0,
            'biaya_lain' => str_replace('.', '', $request->biaya_lain ?? 0) ?: 0,
        ]);

                $action = $request->input('action', 'submit');

        $rules = [
            'nis' => 'required',
            'periode_id' => 'required',
        ];

        if ($action === 'submit') {
            $rules['lembaga_1_id'] = 'required';
            $rules['kelas_pendidikan_1'] = 'required';
            $rules['jumlah_utap'] = 'required|numeric';
            $rules['spp_gasal'] = 'required|numeric';
            $rules['spp_genap'] = 'required|numeric';
            $rules['living_cost'] = 'required|numeric';
            $rules['biaya_lain'] = 'required|numeric';
        }

        $request->validate($rules, [
            'required' => 'Kolom :attribute wajib diisi.',
            'numeric' => 'Kolom :attribute harus berupa angka.',
        ], [
            'nis' => 'NIS',
            'periode_id' => 'Periode Pendaftaran',
            'lembaga_1_id' => 'Lembaga 1',
            'kelas_pendidikan_1' => 'Kelas Pendidikan 1',
            'jumlah_utap' => 'Jumlah UTAP',
            'spp_gasal' => 'SPP Gasal',
            'spp_genap' => 'SPP Genap',
            'living_cost' => 'Living Cost',
            'biaya_lain' => 'Biaya Lain',
        ]);

        DB::beginTransaction();
        try {
            
            // Race condition check for quota
            $periode = MasterPeriode::findOrFail($request->periode_id);
            $jumlahPengajuan = PengajuanBeasiswa::where('periode_id', $periode->id)->lockForUpdate()->count();
            
            if ($jumlahPengajuan >= $periode->kuota) {
                DB::rollBack();
                return redirect()->route('beasiswa.index')->with('error', 'Mohon maaf, kuota pendaftaran untuk gelombang ini sudah penuh.');
            }
            // Update Santri record if data is provided
            $santri = Santri::where('nis', $request->nis)->first();
            if ($santri) {
                $santri->update([
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'tempat_lahir' => $request->tempat_lahir,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'kamar' => $request->kamar,
                    'nama_wali' => $request->nama_wali,
                    'pekerjaan_wali' => $request->pekerjaan_wali,
                    'alamat_lengkap' => $request->alamat_lengkap,
                    'no_hp_wali' => $request->no_hp_wali,
                    'anak_ke' => $request->anak_ke,
                    'jumlah_saudara' => $request->jumlah_saudara,
                    'provinsi_id' => $request->provinsi_id,
                    'kabupaten_id' => $request->kabupaten_id,
                    'kecamatan_id' => $request->kecamatan_id,
                    'desa_id' => $request->desa_id,
                ]);
            }

            $pengajuan = PengajuanBeasiswa::create([
                'nis' => $request->nis,
                'periode_id' => $request->periode_id,
                'lembaga_1_id' => $request->lembaga_1_id,
                'kelas_pendidikan_1' => $request->kelas_pendidikan_1,
                'lembaga_2_id' => $request->lembaga_2_id,
                'kelas_pendidikan_2' => $request->kelas_pendidikan_2,
                'lembaga_qiroati_id' => $request->lembaga_qiroati_id,
                'kelas_qiroati' => $request->kelas_qiroati,
                'jumlah_utap' => $request->jumlah_utap,
                'spp_gasal' => $request->spp_gasal,
                'spp_genap' => $request->spp_genap,
                'living_cost' => $request->living_cost,
                'biaya_lain' => $request->biaya_lain,
                'prestasi_kamar' => $request->prestasi_kamar,
                'isian_bhs' => $request->isian_bhs,
                'kondisi_ekonomi' => $request->kondisi_ekonomi,
                'prestasi_qiroati' => $request->prestasi_qiroati,
                'prestasi_madrasah' => $request->prestasi_madrasah,
                'prestasi_sekolah' => $request->prestasi_sekolah,
                'status_pengajuan' => $action === 'draft' ? 'Draft' : 'Proses',
                'tanggal_bantuan' => date('Y-m-d'),
            ]);

            // Handle tanda tangan digital Base64 Pemohon (Limit max 500KB string)
            if ($request->filled('tanda_tangan_base64') && strlen($request->tanda_tangan_base64) <= 500000) {
                $base64Image = $request->tanda_tangan_base64;
                if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
                    $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
                    $type = strtolower($type[1]); // png
                    $imageBinary = base64_decode($base64Image);
                    if ($imageBinary !== false) {
                        $ttdFilename = 'tanda_tangan/ttd_' . $pengajuan->nis . '_' . time() . '.' . $type;
                        Storage::disk('public')->put($ttdFilename, $imageBinary);
                        $pengajuan->update(['tanda_tangan' => $ttdFilename]);
                    }
                }
            }

            // Handle tanda tangan digital Base64 Wali (Limit max 500KB string)
            if ($request->filled('tanda_tangan_wali_base64') && strlen($request->tanda_tangan_wali_base64) <= 500000) {
                $base64ImageWali = $request->tanda_tangan_wali_base64;
                if (preg_match('/^data:image\/(\w+);base64,/', $base64ImageWali, $typeWali)) {
                    $base64ImageWali = substr($base64ImageWali, strpos($base64ImageWali, ',') + 1);
                    $typeWali = strtolower($typeWali[1]); // png
                    $imageBinaryWali = base64_decode($base64ImageWali);
                    if ($imageBinaryWali !== false) {
                        $ttdWaliFilename = 'tanda_tangan/ttd_wali_' . $pengajuan->nis . '_' . time() . '.' . $typeWali;
                        Storage::disk('public')->put($ttdWaliFilename, $imageBinaryWali);
                        $pengajuan->update(['tanda_tangan_wali' => $ttdWaliFilename]);
                    }
                }
            }

            // Handle file uploads
            if ($request->has('dokumen')) {
                $year = date('Y');
                $month = date('m');
                $pathStr = "dokumen/{$year}/{$month}";
                
                foreach ($request->file('dokumen') as $jenis_dokumen => $file) {
                    if ($file->isValid()) {
                        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                        $path = $file->storeAs($pathStr, $filename, 'public');
                        
                        DokumenPengajuan::create([
                            'pengajuan_id' => $pengajuan->id,
                            'jenis_dokumen' => $jenis_dokumen,
                            'file_path' => $path,
                            'file_size' => $file->getSize(),
                        ]);
                    }
                }
            }

            // Catat histori status
            \App\Models\HistoriStatusBeasiswa::create([
                'pengajuan_id' => $pengajuan->id,
                'status' => $action === 'draft' ? 'Draft' : 'Proses',
                'tanggal_bantuan' => date('Y-m-d'),
                'catatan' => null,
            ]);

            DB::commit();
            return redirect()->route('beasiswa.index')->with('success', $action === 'draft' ? 'Draf Pengajuan Beasiswa berhasil disimpan.' : 'Pengajuan Beasiswa berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan pengajuan: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $pengajuan = PengajuanBeasiswa::forUser()->with([
            'santri', 'dokumen', 'periode', 
            'lembaga1', 'lembaga2', 'lembagaQiroati',
            'santri.provinsi', 'santri.kabupaten', 'santri.kecamatan', 'santri.desa'
        ])->findOrFail($id);
        
        return view('beasiswa.show', compact('pengajuan'));
    }

    public function edit($id)
    {
        $pengajuan = PengajuanBeasiswa::forUser()->with(['santri', 'dokumen'])->findOrFail($id);
        $periode = MasterPeriode::where('status', 'aktif')->first();

        // Data for dropdowns
        $lembagas = Lembaga::orderBy('nama_lembaga')->get();

        return view('beasiswa.edit', compact('pengajuan', 'periode', 'lembagas'));
    }

    public function update(Request $request, $id)
    {
        $pengajuan = PengajuanBeasiswa::forUser()->findOrFail($id);
        // Remove dot separators
                $request->merge([
            'jumlah_utap' => str_replace('.', '', $request->jumlah_utap ?? 0) ?: 0,
            'spp_gasal' => str_replace('.', '', $request->spp_gasal ?? 0) ?: 0,
            'spp_genap' => str_replace('.', '', $request->spp_genap ?? 0) ?: 0,
            'living_cost' => str_replace('.', '', $request->living_cost ?? 0) ?: 0,
            'biaya_lain' => str_replace('.', '', $request->biaya_lain ?? 0) ?: 0,
        ]);

                $action = $request->input('action', 'submit');

        $rules = [];

        if ($action === 'submit') {
            $rules = [
                'lembaga_1_id' => 'required',
                'kelas_pendidikan_1' => 'required',
                'jumlah_utap' => 'required|numeric',
                'spp_gasal' => 'required|numeric',
                'spp_genap' => 'required|numeric',
                'living_cost' => 'required|numeric',
                'biaya_lain' => 'required|numeric',
            ];
        }

        if (!empty($rules)) {
            $request->validate($rules, [
                'required' => 'Kolom :attribute wajib diisi.',
                'numeric' => 'Kolom :attribute harus berupa angka.',
            ], [
                'lembaga_1_id' => 'Lembaga 1',
                'kelas_pendidikan_1' => 'Kelas Pendidikan 1',
                'jumlah_utap' => 'Jumlah UTAP',
                'spp_gasal' => 'SPP Gasal',
                'spp_genap' => 'SPP Genap',
                'living_cost' => 'Living Cost',
                'biaya_lain' => 'Biaya Lain',
            ]);
        }

        $pengajuan = PengajuanBeasiswa::findOrFail($id);

        DB::beginTransaction();
        try {
            // Update Santri record if data is provided
            $santri = Santri::where('nis', $pengajuan->nis)->first();
            if ($santri) {
                $santri->update([
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'tempat_lahir' => $request->tempat_lahir,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'kamar' => $request->kamar,
                    'nama_wali' => $request->nama_wali,
                    'pekerjaan_wali' => $request->pekerjaan_wali,
                    'alamat_lengkap' => $request->alamat_lengkap,
                    'no_hp_wali' => $request->no_hp_wali,
                    'anak_ke' => $request->anak_ke,
                    'jumlah_saudara' => $request->jumlah_saudara,
                    'provinsi_id' => $request->provinsi_id,
                    'kabupaten_id' => $request->kabupaten_id,
                    'kecamatan_id' => $request->kecamatan_id,
                    'desa_id' => $request->desa_id,
                ]);
            }

            $updateData = [
                'lembaga_1_id' => $request->lembaga_1_id,
                'kelas_pendidikan_1' => $request->kelas_pendidikan_1,
                'lembaga_2_id' => $request->lembaga_2_id,
                'kelas_pendidikan_2' => $request->kelas_pendidikan_2,
                'lembaga_qiroati_id' => $request->lembaga_qiroati_id,
                'kelas_qiroati' => $request->kelas_qiroati,
                'jumlah_utap' => $request->jumlah_utap,
                'spp_gasal' => $request->spp_gasal,
                'spp_genap' => $request->spp_genap,
                'living_cost' => $request->living_cost,
                'biaya_lain' => $request->biaya_lain,
                'prestasi_kamar' => $request->prestasi_kamar,
                'isian_bhs' => $request->isian_bhs,
                'kondisi_ekonomi' => $request->kondisi_ekonomi,
                'prestasi_qiroati' => $request->prestasi_qiroati,
                'prestasi_madrasah' => $request->prestasi_madrasah,
                'prestasi_sekolah' => $request->prestasi_sekolah,
            ];

            if ($action === 'submit' && strtolower($pengajuan->status_pengajuan) === 'draft') {
                $updateData['status_pengajuan'] = 'Proses';
                
                \App\Models\HistoriStatusBeasiswa::create([
                    'pengajuan_id' => $pengajuan->id,
                    'status' => 'Proses',
                    'tanggal_bantuan' => date('Y-m-d'),
                    'catatan' => null,
                ]);
            }
            
            $pengajuan->update($updateData);

            // Handle update/hapus tanda tangan digital Base64 Pemohon
            if ($request->tanda_tangan_base64 === 'REMOVE') {
                if ($pengajuan->tanda_tangan && Storage::disk('public')->exists($pengajuan->tanda_tangan)) {
                    Storage::disk('public')->delete($pengajuan->tanda_tangan);
                }
                $pengajuan->update(['tanda_tangan' => null]);
            } elseif ($request->filled('tanda_tangan_base64') && strlen($request->tanda_tangan_base64) <= 500000) {
                $base64Image = $request->tanda_tangan_base64;
                if (preg_match('/^data:image\/(\w+);base64,/', $base64Image, $type)) {
                    $base64Image = substr($base64Image, strpos($base64Image, ',') + 1);
                    $type = strtolower($type[1]); // png
                    $imageBinary = base64_decode($base64Image);
                    if ($imageBinary !== false) {
                        // Hapus file tanda tangan lama jika ada
                        if ($pengajuan->tanda_tangan && Storage::disk('public')->exists($pengajuan->tanda_tangan)) {
                            Storage::disk('public')->delete($pengajuan->tanda_tangan);
                        }
                        $ttdFilename = 'tanda_tangan/ttd_' . $pengajuan->nis . '_' . time() . '.' . $type;
                        Storage::disk('public')->put($ttdFilename, $imageBinary);
                        $pengajuan->update(['tanda_tangan' => $ttdFilename]);
                    }
                }
            }

            // Handle update/hapus tanda tangan digital Base64 Wali
            if ($request->tanda_tangan_wali_base64 === 'REMOVE') {
                if ($pengajuan->tanda_tangan_wali && Storage::disk('public')->exists($pengajuan->tanda_tangan_wali)) {
                    Storage::disk('public')->delete($pengajuan->tanda_tangan_wali);
                }
                $pengajuan->update(['tanda_tangan_wali' => null]);
            } elseif ($request->filled('tanda_tangan_wali_base64') && strlen($request->tanda_tangan_wali_base64) <= 500000) {
                $base64ImageWali = $request->tanda_tangan_wali_base64;
                if (preg_match('/^data:image\/(\w+);base64,/', $base64ImageWali, $typeWali)) {
                    $base64ImageWali = substr($base64ImageWali, strpos($base64ImageWali, ',') + 1);
                    $typeWali = strtolower($typeWali[1]); // png
                    $imageBinaryWali = base64_decode($base64ImageWali);
                    if ($imageBinaryWali !== false) {
                        // Hapus file tanda tangan wali lama jika ada
                        if ($pengajuan->tanda_tangan_wali && Storage::disk('public')->exists($pengajuan->tanda_tangan_wali)) {
                            Storage::disk('public')->delete($pengajuan->tanda_tangan_wali);
                        }
                        $ttdWaliFilename = 'tanda_tangan/ttd_wali_' . $pengajuan->nis . '_' . time() . '.' . $typeWali;
                        Storage::disk('public')->put($ttdWaliFilename, $imageBinaryWali);
                        $pengajuan->update(['tanda_tangan_wali' => $ttdWaliFilename]);
                    }
                }
            }

            // Handle file uploads during edit
            if ($request->has('dokumen')) {
                $year = date('Y');
                $month = date('m');
                $pathStr = "dokumen/{$year}/{$month}";
                
                foreach ($request->file('dokumen') as $jenis_dokumen => $file) {
                    if ($file->isValid()) {
                        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                        $path = $file->storeAs($pathStr, $filename, 'public');
                        
                        $existingDoc = DokumenPengajuan::where('pengajuan_id', $pengajuan->id)
                                        ->where('jenis_dokumen', $jenis_dokumen)
                                        ->first();
                        
                        if ($existingDoc) {
                            // Hapus file lama jika ada
                            if (Storage::disk('public')->exists($existingDoc->file_path)) {
                                Storage::disk('public')->delete($existingDoc->file_path);
                            }
                            // Update record
                            $existingDoc->update([
                                'file_path' => $path,
                                'file_size' => $file->getSize(),
                            ]);
                        } else {
                            // Create new record
                            DokumenPengajuan::create([
                                'pengajuan_id' => $pengajuan->id,
                                'jenis_dokumen' => $jenis_dokumen,
                                'file_path' => $path,
                                'file_size' => $file->getSize(),
                            ]);
                        }
                    }
                }
            }

            DB::commit();

            if ($request->input('preview_pdf') == '1') {
                return redirect()->route('beasiswa.pdf', $pengajuan->id);
            }

            return redirect()->route('beasiswa.index')->with('success', $action === 'draft' ? 'Draf Pengajuan Beasiswa berhasil disimpan.' : 'Pengajuan Beasiswa berhasil diubah.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal mengubah pengajuan: ' . $e->getMessage())->withInput();
        }
    }

    // Endpoint to list documents for a specific pengajuan
    public function getDokumen($id)
    {
        $dokumen = DokumenPengajuan::where('pengajuan_id', $id)->get();
        return response()->json($dokumen);
    }

    public function uploadDokumen(Request $request, $id)
    {
        $request->validate([
            'jenis_dokumen' => 'required|string|max:50',
            'file_dokumen' => 'required|mimes:pdf|max:5120',
        ]);

        $pengajuan = PengajuanBeasiswa::findOrFail($id);
        
        DB::beginTransaction();
        try {
            $file = $request->file('file_dokumen');
            
            // Simpan file fisik ke private disk storage/app/dokumen/{Tahun}/{Bulan}/nama-file-uuid.pdf
            $year = date('Y');
            $month = date('m');
            $pathStr = "dokumen/{$year}/{$month}";
            
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs($pathStr, $filename, 'public');
            
            DokumenPengajuan::create([
                'pengajuan_id' => $pengajuan->id,
                'jenis_dokumen' => $request->jenis_dokumen,
                'file_path' => $path,
                'file_size' => $file->getSize(),
            ]);
            
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Dokumen berhasil diunggah']);
            
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal mengunggah dokumen: ' . $e->getMessage()], 500);
        }
    }

    public function lihatDokumen($id)
    {
        $dokumen = DokumenPengajuan::findOrFail($id);
        
        $fullPath = Storage::disk('public')->path($dokumen->file_path);
        
        if (!file_exists($fullPath)) {
            abort(404, 'File dokumen tidak ditemukan di server.');
        }
        
        return response()->file($fullPath);
    }

    public function getHistoriStatus($id)
    {
        $histori = \App\Models\HistoriStatusBeasiswa::where('pengajuan_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($histori);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_pengajuan' => 'required|string|in:draft,proses,acc,pencairan',
            'tanggal_bantuan' => 'nullable|date',
            'catatan' => 'nullable|string'
        ]);

        $pengajuan = PengajuanBeasiswa::forUser()->findOrFail($id);

        DB::beginTransaction();
        try {
            // Update main status
            $pengajuan->update([
                'status_pengajuan' => $request->status_pengajuan,
                'tanggal_bantuan' => $request->tanggal_bantuan ?? $pengajuan->tanggal_bantuan
            ]);

            // Insert to history
            \App\Models\HistoriStatusBeasiswa::create([
                'pengajuan_id' => $pengajuan->id,
                'status' => $request->status_pengajuan,
                'tanggal_bantuan' => $request->tanggal_bantuan,
                'catatan' => $request->catatan,
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Status berhasil diperbarui']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui status: ' . $e->getMessage()], 500);
        }
    }

    public function cetakPdf($id)
    {
        $pengajuan = PengajuanBeasiswa::forUser()->with(['santri.provinsi', 'santri.kabupaten', 'santri.kecamatan', 'santri.desa', 'lembaga1', 'lembaga2', 'lembagaQiroati', 'periode', 'dokumen'])->findOrFail($id);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('beasiswa.pdf', compact('pengajuan'));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('Form_Pengajuan_' . $pengajuan->nis . '.pdf');
    }
}
