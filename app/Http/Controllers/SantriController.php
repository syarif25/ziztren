<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Santri;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SantriController extends Controller
{
    /**
     * Display the Santri view.
     */
    public function index()
    {
        return view('santri');
    }

    /**
     * Return JSON for DataTables Server-Side Processing
     */
    public function data()
    {
        $santris = Santri::query()
            ->whereRaw('nama_lengkap REGEXP "[a-zA-Z]"')
            ->orderBy('nama_lengkap', 'asc');

        return DataTables::of($santris)
            ->addIndexColumn()
            ->addColumn('ttl', function ($row) {
                $tempat = $row->tempat_lahir ?: '-';
                $tanggal = $row->tanggal_lahir ? date('d/m/Y', strtotime($row->tanggal_lahir)) : '-';
                return $tempat . ', ' . $tanggal;
            })
            ->make(true);
    }

    /**
     * Sync Data from E-Registrasi API
     */
    public function sync()
    {
        $token = config('services.eregistrasi.token');
        $baseUrl = rtrim(config('services.eregistrasi.url', 'https://beeregistrasiv2.p2s3.com/api/v1'), '/') . '/integration/siswa';

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token API e-Registrasi (EREGISTRASI_API_TOKEN) belum dikonfigurasi di file .env.'
            ], 400);
        }

        try {
            $limit = 1000;
            $page = 1;
            $totalSynced = 0;
            $now = now();
            
            do {
                $response = Http::timeout(30)->withToken($token)->get($baseUrl, [
                    'page' => $page,
                    'limit' => $limit,
                ]);

                if (!$response->successful()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal terhubung ke API E-Registrasi: ' . $response->status()
                    ], 500);
                }

                $responseData = $response->json();
                
                if (!isset($responseData['success']) || !$responseData['success']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'API Error: ' . ($responseData['message'] ?? 'Respon tidak valid')
                    ], 500);
                }

                $payload = $responseData['data'] ?? [];
                $santriList = $payload['siswa'] ?? [];
                
                if (!empty($santriList)) {
                    $batch = [];
                    foreach ($santriList as $item) {
                        if (!empty($item['nis'])) {
                            $batch[] = [
                                'nis' => $item['nis'],
                                'nama_lengkap' => $item['nama_lengkap'] ?? '-',
                                'created_at' => $now,
                                'updated_at' => $now,
                            ];
                        }
                    }

                    // Bulk Upsert per batch 500/1000 record dalam 1 query SQL
                    if (!empty($batch)) {
                        Santri::upsert($batch, ['nis'], ['nama_lengkap', 'updated_at']);
                        $totalSynced += count($batch);
                    }
                }

                $totalPages = $payload['total_pages'] ?? 1;
                $page++;

            } while ($page <= $totalPages);

            return response()->json([
                'success' => true,
                'message' => 'Data santri berhasil disinkronisasi. Total: ' . number_format($totalSynced, 0, ',', '.') . ' santri.',
                'total' => $totalSynced,
            ]);

        } catch (\Exception $e) {
            Log::error('Santri Sync Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem saat sinkronisasi.'
            ], 500);
        }
    }
}
