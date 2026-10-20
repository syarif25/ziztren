<?php

namespace App\Http\Controllers;

use App\Models\Lembaga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\Facades\DataTables;

class LembagaController extends Controller
{
    /**
     * Sinkronisasi data lembaga dari API e-Registrasi (VPS / Local)
     */
    public function syncFromEregistrasi()
    {
        $apiUrl = config('services.eregistrasi.url', 'https://beeregistrasiv2.p2s3.com/api/v1');
        $serviceKey = config('services.eregistrasi.service_key');

        if (!$serviceKey) {
            return response()->json([
                'success' => false,
                'message' => 'Kunci rahasia (EREGISTRASI_SERVICE_KEY) belum dikonfigurasi di file .env.'
            ], 400);
        }

        try {
            // Panggil API internal e-Registrasi dengan header rahasia
            $response = Http::timeout(15)
                ->withHeaders([
                    'X-Service-Key' => $serviceKey,
                    'Accept' => 'application/json',
                ])
                ->get(rtrim($apiUrl, '/') . '/internal/pendidikan');

            if (!$response->successful()) {
                if ($response->status() === 404 || $response->status() === 401 || $response->status() === 403) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal terhubung ke API e-Registrasi (Status: ' . $response->status() . '). Pastikan pembaruan be_eregistrasiv2 sudah di-deploy ke VPS.'
                    ], 502);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengambil data dari e-Registrasi: ' . ($response->json('message') ?? $response->body())
                ], 500);
            }

            $result = $response->json();
            $dataLembaga = $result['data'] ?? [];

            if (empty($dataLembaga)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tidak ada data lembaga yang ditemukan dari e-Registrasi.',
                    'count' => 0
                ]);
            }

            $inserted = 0;
            $updated = 0;

            DB::transaction(function () use ($dataLembaga, &$inserted, &$updated) {
                foreach ($dataLembaga as $item) {
                    $namaLembaga = trim($item['nama_pendidikan'] ?? '');
                    if (!$namaLembaga) continue;

                    $kode = trim($item['singkatan'] ?? '');
                    if (!$kode) {
                        $kode = 'LMB-' . $item['id_pendidikan'];
                    }

                    // Tentukan jenis lembaga: jika madrasah diniyah -> 'sore', sekolah formal / dikti -> 'pagi'
                    $tingkatan = strtoupper($item['tingkatan'] ?? '');
                    $jenis = str_contains($tingkatan, 'MADRASAH') ? 'sore' : 'pagi';

                    // Cari berdasarkan kode_lembaga atau nama_lembaga
                    $lembaga = Lembaga::where('kode_lembaga', $kode)
                        ->orWhere('nama_lembaga', $namaLembaga)
                        ->first();

                    if ($lembaga) {
                        $lembaga->update([
                            'kode_lembaga' => $kode,
                            'nama_lembaga' => $namaLembaga,
                            'bidang' => $item['tingkatan'] ?? $lembaga->bidang,
                            'jenis_lembaga' => $jenis,
                        ]);
                        $updated++;
                    } else {
                        Lembaga::create([
                            'kode_lembaga' => $kode,
                            'nama_lembaga' => $namaLembaga,
                            'bidang' => $item['tingkatan'] ?? 'UMUM',
                            'jenis_lembaga' => $jenis,
                        ]);
                        $inserted++;
                    }
                }
            });

            return response()->json([
                'success' => true,
                'message' => "Berhasil menyinkronkan {$inserted} lembaga baru dan memperbarui {$updated} data lembaga dari e-Registrasi.",
                'inserted' => $inserted,
                'updated' => $updated,
                'total' => count($dataLembaga)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Koneksi ke API e-Registrasi bermasalah: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('lembaga');
    }

    /**
     * Data untuk DataTables Server-Side Processing
     */
    public function data()
    {
        $lembagas = Lembaga::select(['id', 'kode_lembaga', 'nama_lembaga', 'bidang']);

        return DataTables::of($lembagas)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $btn = '<button class="btn btn-sm btn-info edit-btn" data-id="' . $row->id . '"><i class="ri-pencil-line"></i></button> ';
                $btn .= '<button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '"><i class="ri-delete-bin-line"></i></button>';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_lembaga' => 'required|string|max:50|unique:lembagas,kode_lembaga',
            'nama_lembaga' => 'required|string|max:150',
            'bidang' => 'nullable|string|max:100',
        ]);

        try {
            Lembaga::create($validated);
            return response()->json(['success' => true, 'message' => 'Lembaga berhasil ditambahkan.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $lembaga = Lembaga::findOrFail($id);
        return response()->json($lembaga);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $lembaga = Lembaga::findOrFail($id);

        $validated = $request->validate([
            'kode_lembaga' => 'required|string|max:50|unique:lembagas,kode_lembaga,' . $lembaga->id,
            'nama_lembaga' => 'required|string|max:150',
            'bidang' => 'nullable|string|max:100',
        ]);

        try {
            $lembaga->update($validated);
            return response()->json(['success' => true, 'message' => 'Lembaga berhasil diperbarui.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $lembaga = Lembaga::findOrFail($id);
            $lembaga->delete();
            return response()->json(['success' => true, 'message' => 'Lembaga berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
