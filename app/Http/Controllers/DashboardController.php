<?php

namespace App\Http\Controllers;

use App\Models\Lembaga;
use App\Models\MasterPeriode;
use App\Models\PengajuanBeasiswa;
use App\Models\Santri;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $periodeId = $request->get('periode_id');
        $periodes = MasterPeriode::orderBy('tahun_masehi', 'desc')->orderBy('gelombang', 'asc')->get();
        $activePeriode = MasterPeriode::where('status', 'aktif')->first();

        // Base query with optional filter (scoped per gender jika operator)
        $query = PengajuanBeasiswa::forUser();
        if ($periodeId && $periodeId !== 'all') {
            $query->where('periode_id', $periodeId);
        }

        // 1. KPI Counts
        $totalPengajuan = (clone $query)->count();
        $totalSantri = Santri::forUser()->count();

        $countDraft = (clone $query)->where('status_pengajuan', 'draft')->count();
        $countProses = (clone $query)->where('status_pengajuan', 'proses')->count();
        $countAcc = (clone $query)->whereRaw('LOWER(status_pengajuan) = ?', ['acc'])->count();
        $countPencairan = (clone $query)->whereRaw('LOWER(status_pengajuan) = ?', ['pencairan'])->count();
        $countDitolak = (clone $query)->whereRaw('LOWER(status_pengajuan) = ?', ['ditolak'])->count();

        // 2. Finansial
        $totalDanaUsulan = (clone $query)->selectRaw(
            'SUM(COALESCE(jumlah_utap,0) + COALESCE(spp_gasal,0) + COALESCE(spp_genap,0) + COALESCE(living_cost,0) + COALESCE(biaya_lain,0)) as total'
        )->value('total') ?? 0;

        $totalDanaAcc = (clone $query)->whereRaw('LOWER(status_pengajuan) = ?', ['acc'])->selectRaw(
            'SUM(COALESCE(jumlah_utap,0) + COALESCE(spp_gasal,0) + COALESCE(spp_genap,0) + COALESCE(living_cost,0) + COALESCE(biaya_lain,0)) as total'
        )->value('total') ?? 0;

        $totalDanaCair = (clone $query)->whereRaw('LOWER(status_pengajuan) = ?', ['pencairan'])->selectRaw(
            'SUM(COALESCE(jumlah_utap,0) + COALESCE(spp_gasal,0) + COALESCE(spp_genap,0) + COALESCE(living_cost,0) + COALESCE(biaya_lain,0)) as total'
        )->value('total') ?? 0;

        // 3. Status Kuota Periode Aktif
        $kuotaTotal = $activePeriode ? (int) $activePeriode->kuota : 0;
        $kuotaTerpakai = $activePeriode ? PengajuanBeasiswa::where('periode_id', $activePeriode->id)->count() : 0;
        $kuotaSisa = max(0, $kuotaTotal - $kuotaTerpakai);
        $kuotaPersen = $kuotaTotal > 0 ? min(100, round(($kuotaTerpakai / $kuotaTotal) * 100)) : 0;

        // 4. Breakdown Komponen Biaya Bantuan
        $komponenBiaya = (clone $query)->selectRaw('
            SUM(COALESCE(jumlah_utap,0)) as utap,
            SUM(COALESCE(spp_gasal,0)) as spp_gasal,
            SUM(COALESCE(spp_genap,0)) as spp_genap,
            SUM(COALESCE(living_cost,0)) as living_cost,
            SUM(COALESCE(biaya_lain,0)) as biaya_lain
        ')->first();

        $komponenList = [
            [
                'nama' => 'UTAP (Uang Tahunan Pesantren)',
                'nominal' => (float) ($komponenBiaya->utap ?? 0),
                'persen' => $totalDanaUsulan > 0 ? round((($komponenBiaya->utap ?? 0) / $totalDanaUsulan) * 100, 1) : 0,
                'color' => '#0f9cf3',
            ],
            [
                'nama' => 'SPP Genap',
                'nominal' => (float) ($komponenBiaya->spp_genap ?? 0),
                'persen' => $totalDanaUsulan > 0 ? round((($komponenBiaya->spp_genap ?? 0) / $totalDanaUsulan) * 100, 1) : 0,
                'color' => '#11c46e',
            ],
            [
                'nama' => 'Living Cost (Uang Saku/Makan)',
                'nominal' => (float) ($komponenBiaya->living_cost ?? 0),
                'persen' => $totalDanaUsulan > 0 ? round((($komponenBiaya->living_cost ?? 0) / $totalDanaUsulan) * 100, 1) : 0,
                'color' => '#009688',
            ],
            [
                'nama' => 'Biaya Lain-lain',
                'nominal' => (float) ($komponenBiaya->biaya_lain ?? 0),
                'persen' => $totalDanaUsulan > 0 ? round((($komponenBiaya->biaya_lain ?? 0) / $totalDanaUsulan) * 100, 1) : 0,
                'color' => '#f1b44c',
            ],
            [
                'nama' => 'SPP Gasal',
                'nominal' => (float) ($komponenBiaya->spp_gasal ?? 0),
                'persen' => $totalDanaUsulan > 0 ? round((($komponenBiaya->spp_gasal ?? 0) / $totalDanaUsulan) * 100, 1) : 0,
                'color' => '#fb4d53',
            ],
        ];

        // 5. Statistik Lembaga
        $lembagaStats = (clone $query)
            ->join('lembagas', 'pengajuan_beasiswas.lembaga_1_id', '=', 'lembagas.id')
            ->select(
                'lembagas.nama_lembaga',
                DB::raw('count(*) as total_santri'),
                DB::raw('SUM(COALESCE(jumlah_utap,0) + COALESCE(spp_gasal,0) + COALESCE(spp_genap,0) + COALESCE(living_cost,0) + COALESCE(biaya_lain,0)) as total_nominal')
            )
            ->groupBy('lembagas.id', 'lembagas.nama_lembaga')
            ->orderByDesc('total_santri')
            ->take(5)
            ->get();

        // 6. Antrean Verifikasi (Status 'proses' atau 'draft')
        $antreanVerifikasi = (clone $query)
            ->with(['santri', 'periode', 'lembaga1'])
            ->whereIn('status_pengajuan', ['proses', 'draft'])
            ->latest()
            ->take(6)
            ->get();

        // 7. Pengajuan Terbaru (Semua status untuk audit trail)
        $pengajuanTerbaru = (clone $query)
            ->with(['santri', 'periode', 'lembaga1'])
            ->latest()
            ->take(8)
            ->get();

        return view('dashboard', compact(
            'periodes',
            'activePeriode',
            'periodeId',
            'totalPengajuan',
            'totalSantri',
            'countDraft',
            'countProses',
            'countAcc',
            'countPencairan',
            'countDitolak',
            'totalDanaUsulan',
            'totalDanaAcc',
            'totalDanaCair',
            'kuotaTotal',
            'kuotaTerpakai',
            'kuotaSisa',
            'kuotaPersen',
            'komponenList',
            'lembagaStats',
            'antreanVerifikasi',
            'pengajuanTerbaru'
        ));
    }
}
