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
        // Bearer token from the documentation
        $token = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJyb2xlIjoiaW50ZWdyYXRpb24iLCJpYXQiOjE3ODYyNDM1MDMsImV4cCI6MjEwMTgxOTUwM30.q2pHLV3XIDK5959WvmmUg1C5LreQm1NlGU8sU-c4LVo';
        $baseUrl = 'https://beeregistrasiv2.p2s3.com/api/v1/integration/siswa';

        try {
            $limit = 1000;
            $page = 1;
            
            do {
                $response = Http::withToken($token)->get($baseUrl, [
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
                
                if (!$responseData['success']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'API Error: ' . $responseData['message']
                    ], 500);
                }

                $payload = $responseData['data'];
                $santriList = $payload['siswa'] ?? [];
                
                foreach ($santriList as $item) {
                    Santri::updateOrCreate(
                        ['nis' => $item['nis']],
                        ['nama_lengkap' => $item['nama_lengkap']]
                    );
                }

                $totalPages = $payload['total_pages'] ?? 1;
                $page++;

            } while ($page <= $totalPages);

            return response()->json([
                'success' => true,
                'message' => 'Data santri berhasil disinkronisasi.'
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
