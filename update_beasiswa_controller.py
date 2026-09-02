import re

filepath = 'app/Http/Controllers/BeasiswaController.php'

with open(filepath, 'r') as f:
    content = f.read()

# Add missing use statements
if 'use App\Models\Santri;' not in content:
    content = content.replace('use App\Models\DokumenPengajuan;', 'use App\Models\DokumenPengajuan;\nuse App\Models\Santri;\nuse App\Models\Lembaga;')

# Insert new methods after data()
target = r'(\s+public function data\(\)\s+\{.*?\s+make\(true\);\s+\})'

new_methods = '''

    public function create()
    {
        $santris = Santri::orderBy('nama_lengkap', 'asc')->get();
        $lembagas = Lembaga::orderBy('nama_lembaga', 'asc')->get();
        return view('beasiswa.create', compact('santris', 'lembagas'));
    }

    public function getSantriDetail($nis)
    {
        $santri = Santri::where('nis', $nis)->first();
        if ($santri) {
            return response()->json($santri);
        }
        return response()->json(null, 404);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required',
            'tahun_pelajaran' => 'required',
            'gelombang' => 'required',
            'lembaga_1_id' => 'required',
            'kelas_pendidikan_1' => 'required',
            'jumlah_utap' => 'required|numeric',
            'spp_gasal' => 'required|numeric',
            'spp_genap' => 'required|numeric',
            'living_cost' => 'required|numeric',
            'biaya_lain' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            $pengajuan = PengajuanBeasiswa::create([
                'nis' => $request->nis,
                'tahun_pelajaran' => $request->tahun_pelajaran,
                'gelombang' => $request->gelombang,
                'lembaga_1_id' => $request->lembaga_1_id,
                'kelas_pendidikan_1' => $request->kelas_pendidikan_1,
                'lembaga_2_id' => $request->lembaga_2_id,
                'kelas_pendidikan_2' => $request->kelas_pendidikan_2,
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
                'status_pengajuan' => 'pending',
                'tanggal_bantuan' => date('Y-m-d'),
            ]);

            // Handle file uploads
            if ($request->hasFile('dokumen')) {
                $year = date('Y');
                $month = date('m');
                $pathStr = "dokumen/{$year}/{$month}";
                
                foreach ($request->file('dokumen') as $jenis_dokumen => $file) {
                    if ($file->isValid()) {
                        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
                        $path = $file->storeAs($pathStr, $filename, 'local');
                        
                        DokumenPengajuan::create([
                            'pengajuan_id' => $pengajuan->id,
                            'jenis_dokumen' => $jenis_dokumen,
                            'file_path' => $path,
                            'file_size' => $file->getSize(),
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('beasiswa.index')->with('success', 'Pengajuan Beasiswa berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan pengajuan: ' . $e->getMessage())->withInput();
        }
    }'''

content = re.sub(target, r'\1' + new_methods, content, flags=re.DOTALL)

with open(filepath, 'w') as f:
    f.write(content)

print("Updated BeasiswaController.php")
