@extends('layouts.app')

@section('title', 'Dashboard Beasiswa')
@section('page-title', 'Dashboard')

@push('styles')
    <style>
        .metric-card {
            border: 1px solid #e9ebec;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            background: #fff;
            position: relative;
        }
        .metric-card.border-primary-left {
            border-left: 4px solid #0f9cf3;
        }
        .metric-card.border-success-left {
            border-left: 4px solid #11c46e;
        }
        .metric-card.border-warning-left {
            border-left: 4px solid #f1b44c;
        }
        .metric-card.border-info-left {
            border-left: 4px solid #009688;
        }
        .pipeline-step {
            border: 1px solid #e9ebec;
            border-radius: 6px;
            padding: 12px 16px;
            background: #fafbfc;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .pipeline-step.active-step {
            background: #fff;
            border-color: #d0d5dd;
            box-shadow: 0 2px 4px rgba(0,0,0,0.03);
        }
    </style>
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="javascript: void(0);">ZIZTREN</a></li>
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    {{-- Top Filter & Periode Bar --}}
    <div class="row align-items-center mb-3">
        <div class="col-md-7">
            <h4 class="font-size-18 text-dark fw-bold mb-1">Pengelolaan & Penyaluran Beasiswa</h4>
            <p class="text-muted font-size-13 mb-0">
                Pondok Pesantren Salafiyah Syafi'iyah Sukorejo 
            </p>
        </div>
        <div class="col-md-5 text-md-end mt-2 mt-md-0">
            <form method="GET" action="{{ route('dashboard') }}" id="formFilterPeriode" class="d-inline-flex align-items-center gap-2">
                <span class="text-muted font-size-13 text-nowrap"><i class="ri-filter-2-line me-1"></i>Periode:</span>
                <select name="periode_id" id="filter_periode" class="form-select form-select-sm" onchange="document.getElementById('formFilterPeriode').submit();" style="min-width: 210px;">
                    <option value="all" {{ empty($periodeId) || $periodeId === 'all' ? 'selected' : '' }}>Semua Periode Pendaftaran</option>
                    @foreach($periodes as $p)
                        <option value="{{ $p->id }}" {{ $periodeId == $p->id ? 'selected' : '' }}>
                            {{ $p->nama_periode }} {{ $p->is_active ? '(Aktif)' : '' }}
                        </option>
                    @endforeach
                </select>
                @if(!empty($periodeId) && $periodeId !== 'all')
                    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-light border" title="Reset"><i class="ri-refresh-line"></i></a>
                @endif
            </form>
        </div>
    </div>

    {{-- Banner Status Kuota Periode Aktif (Jika Ada) --}}
    @if($activePeriode)
    <div class="card mb-3 border-0 bg-light">
        <div class="card-body p-3">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-3 flex-shrink-0">
                            <span class="avatar-title bg-success text-white rounded">
                                <i class="ri-calendar-check-line font-size-20"></i>
                            </span>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <h6 class="font-size-14 text-dark fw-bold mb-0">{{ $activePeriode->nama_periode }}</h6>
                                <span class="badge bg-success font-size-11">Periode Pendaftaran Aktif</span>
                            </div>
                            <small class="text-muted">
                                Pendaftaran: {{ \Carbon\Carbon::parse($activePeriode->tgl_buka)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($activePeriode->tgl_tutup)->format('d M Y') }}
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 mt-2 mt-lg-0">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="font-size-12 text-muted">Pemenuhan Kuota Beasiswa:</span>
                        <span class="font-size-12 fw-bold text-dark">{{ $kuotaTerpakai }} dari {{ $kuotaTotal }} Kuota ({{ $kuotaPersen }}%)</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $kuotaPersen }}%;" aria-valuenow="{{ $kuotaPersen }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between font-size-11 text-muted mt-1">
                        <span>Terisi: {{ $kuotaTerpakai }} santri</span>
                        <span>Sisa kuota: {{ $kuotaSisa }} santri</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Row 1: Executive KPI Cards --}}
    <div class="row g-3 mb-3">
        <div class="col-xl-3 col-md-6">
            <div class="card metric-card border-primary-left h-100 mb-0">
                <div class="card-body p-3">
                    <div class="text-muted text-uppercase font-size-12 fw-semibold">Total Pengajuan</div>
                    <h3 class="fw-bold text-dark my-2">{{ number_format($totalPengajuan, 0, ',', '.') }}</h3>
                   
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card metric-card border-warning-left h-100 mb-0">
                <div class="card-body p-3">
                    <div class="text-muted text-uppercase font-size-12 fw-semibold">Total Usulan Dana</div>
                    <h3 class="fw-bold text-dark my-2">Rp {{ number_format($totalDanaUsulan, 0, ',', '.') }}</h3>
                    
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card metric-card border-info-left h-100 mb-0">
                <div class="card-body p-3">
                    <div class="text-muted text-uppercase font-size-12 fw-semibold">Disetujui (ACC Bendahara)</div>
                    <h3 class="fw-bold text-dark my-2">Rp {{ number_format($totalDanaAcc, 0, ',', '.') }}</h3>
                    <div class="d-flex justify-content-between align-items-center font-size-12 text-muted">
                        <span>Berkas disetujui:</span>
                        <span class="fw-semibold text-primary">{{ $countAcc + $countPencairan }} berkas</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card metric-card border-success-left h-100 mb-0">
                <div class="card-body p-3">
                    <div class="text-muted text-uppercase font-size-12 fw-semibold">Telah Dicairkan</div>
                    <h3 class="fw-bold text-success my-2">Rp {{ number_format($totalDanaCair, 0, ',', '.') }}</h3>
                    
                </div>
            </div>
        </div>
    </div>

    {{-- Row 2: Pipeline Alur Status Berkas --}}
    <div class="card mb-3">
        <div class="card-header bg-transparent py-2 border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-bold text-dark font-size-13"><i class="ri-flow-chart me-1 text-primary"></i> Pipeline Status Berkas Pengajuan</span>
                <a href="{{ route('beasiswa.index') }}" class="font-size-12 text-primary">Lihat Seluruh Berkas &rarr;</a>
            </div>
        </div>
        <div class="card-body p-3">
            <div class="row g-2">
                <div class="col-lg-3 col-6">
                    <div class="pipeline-step">
                        <div>
                            <span class="text-muted font-size-12 d-block">1. Draft Berkas</span>
                            <span class="fw-bold font-size-18 text-secondary">{{ $countDraft }}</span>
                            <small class="text-muted d-block font-size-11">Belum diajukan</small>
                        </div>
                        <span class="badge bg-secondary font-size-11">{{ $totalPengajuan > 0 ? round(($countDraft / $totalPengajuan) * 100) : 0 }}%</span>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="pipeline-step {{ $countProses > 0 ? 'active-step border-warning' : '' }}">
                        <div>
                            <span class="text-muted font-size-12 d-block">2. Verifikasi Berkas</span>
                            <span class="fw-bold font-size-18 text-warning">{{ $countProses }}</span>
                            <small class="text-warning d-block font-size-11 fw-medium">Menunggu telaah</small>
                        </div>
                        <span class="badge bg-warning font-size-11">{{ $totalPengajuan > 0 ? round(($countProses / $totalPengajuan) * 100) : 0 }}%</span>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="pipeline-step">
                        <div>
                            <span class="text-muted font-size-12 d-block">3. Disetujui (ACC)</span>
                            <span class="fw-bold font-size-18 text-primary">{{ $countAcc }}</span>
                            <small class="text-muted d-block font-size-11">Siap disalurkan</small>
                        </div>
                        <span class="badge bg-primary font-size-11">{{ $totalPengajuan > 0 ? round(($countAcc / $totalPengajuan) * 100) : 0 }}%</span>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="pipeline-step">
                        <div>
                            <span class="text-muted font-size-12 d-block">4. Selesai Pencairan</span>
                            <span class="fw-bold font-size-18 text-success">{{ $countPencairan }}</span>
                            <small class="text-muted d-block font-size-11">Telah diterima</small>
                        </div>
                        <span class="badge bg-success font-size-11">{{ $totalPengajuan > 0 ? round(($countPencairan / $totalPengajuan) * 100) : 0 }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 3: Dua Kolom Finansial & Distribusi Lembaga --}}
    <div class="row g-3 mb-3">
        {{-- Kolom Kiri: Rekapitulasi Alokasi Dana per Komponen --}}
        <div class="col-xl-6">
            <div class="card h-100 mb-0">
                <div class="card-header bg-transparent py-2 border-bottom d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-dark font-size-13"><i class="ri-money-cny-box-line me-1 text-success"></i> Alokasi Komponen Pembiayaan</span>
                    <span class="font-size-12 text-muted">Total: <strong class="text-dark">Rp {{ number_format($totalDanaUsulan, 0, ',', '.') }}</strong></span>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless align-middle mb-0">
                            <tbody>
                                @foreach($komponenList as $k)
                                <tr>
                                    <td style="width: 40%;" class="ps-0 py-2">
                                        <div class="d-flex align-items-center">
                                            <span style="display:inline-block; width: 10px; height: 10px; border-radius: 2px; background: {{ $k['color'] }}; margin-right: 8px;"></span>
                                            <span class="font-size-13 text-dark fw-medium">{{ $k['nama'] }}</span>
                                        </div>
                                    </td>
                                    <td style="width: 35%;" class="py-2">
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $k['persen'] }}%; background-color: {{ $k['color'] }};"></div>
                                        </div>
                                    </td>
                                    <td style="width: 25%;" class="text-end pe-0 py-2">
                                        <span class="fw-bold font-size-12 text-dark">Rp {{ number_format($k['nominal'], 0, ',', '.') }}</span>
                                        <small class="text-muted d-block font-size-11">{{ $k['persen'] }}%</small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Distribusi Tingkat Lembaga / Madrasah Santri --}}
        <div class="col-xl-6">
            <div class="card h-100 mb-0">
                <div class="card-header bg-transparent py-2 border-bottom d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-dark font-size-13"><i class="ri-building-4-line me-1 text-info"></i> Sebaran Lembaga / Madrasah</span>
                    <a href="{{ route('laporan.index') }}" class="font-size-12 text-primary">Rekap Lengkap &rarr;</a>
                </div>
                <div class="card-body p-3">
                    @if($lembagaStats->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0">
                                <thead class="table-light font-size-12">
                                    <tr>
                                        <th>Nama Lembaga / Madrasah</th>
                                        <th class="text-center">Jumlah Santri</th>
                                        <th class="text-end">Alokasi Bantuan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($lembagaStats as $l)
                                    <tr>
                                        <td class="font-size-13 fw-medium text-dark">
                                            <i class="ri-graduation-cap-line text-muted me-1"></i> {{ $l->nama_lembaga }}
                                        </td>
                                        <td class="text-center font-size-13 fw-semibold">
                                            {{ $l->total_santri }} Santri
                                        </td>
                                        <td class="text-end font-size-13 fw-bold text-success">
                                            Rp {{ number_format($l->total_nominal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted font-size-13">
                            <i class="ri-file-search-line font-size-24 d-block mb-1"></i>
                            Belum ada pengajuan dengan lembaga yang terdata pada filter ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Row 4: Antrean Verifikasi & Pengajuan Beasiswa Terkini --}}
    <div class="row g-3">
        <div class="col-12">
            <div class="card mb-0">
                <div class="card-header bg-transparent py-2 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <span class="fw-bold text-dark font-size-13"><i class="ri-history-line me-1 text-primary"></i> Data Pengajuan Beasiswa Terkini</span>
                        <span class="text-muted font-size-12 ms-2">(Menampilkan {{ $pengajuanTerbaru->count() }} pengajuan terakhir)</span>
                    </div>
                    <div class="d-flex gap-2">
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered table-centered align-middle mb-0 font-size-13">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40px;" class="text-center">No</th>
                                    <th>NIS</th>
                                    <th>Nama Santri</th>
                                    <th>Lembaga / Tingkat</th>
                                    <th>Periode</th>
                                    <th class="text-end">Nominal Bantuan</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center" style="width: 100px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pengajuanTerbaru as $index => $item)
                                    @php
                                        $nominal = ($item->jumlah_utap ?? 0) + ($item->spp_gasal ?? 0) + ($item->spp_genap ?? 0) + ($item->living_cost ?? 0) + ($item->biaya_lain ?? 0);
                                        $st = strtolower($item->status_pengajuan ?? 'draft');
                                        $badgeCls = match($st) {
                                            'draft'     => 'bg-secondary',
                                            'proses'    => 'bg-warning text-dark',
                                            'acc'       => 'bg-primary',
                                            'pencairan' => 'bg-success',
                                            'ditolak'   => 'bg-danger',
                                            default     => 'bg-secondary'
                                        };
                                        $statusLabel = match($st) {
                                            'draft'     => 'Draft',
                                            'proses'    => 'Verifikasi',
                                            'acc'       => 'Disetujui (ACC)',
                                            'pencairan' => 'Pencairan',
                                            'ditolak'   => 'Ditolak',
                                            default     => ucfirst($st)
                                        };
                                    @endphp
                                    <tr>
                                        <td class="text-center text-muted">{{ $index + 1 }}</td>
                                        <td class="fw-semibold">{{ $item->nis }}</td>
                                        <td>
                                            <span class="fw-bold text-dark">{{ $item->santri?->nama_lengkap ?? '-' }}</span>
                                            @if($item->santri?->asrama)
                                                <small class="text-muted d-block">{{ $item->santri->asrama }} {{ $item->santri->kamar ? '/ Kamar ' . $item->santri->kamar : '' }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $item->lembaga1?->nama_lembaga ?? '-' }}</td>
                                        <td>
                                            <span class="badge bg-light text-muted border">{{ $item->periode?->nama_periode ?? '-' }}</span>
                                        </td>
                                        <td class="text-end fw-bold text-dark">
                                            Rp {{ number_format($nominal, 0, ',', '.') }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge {{ $badgeCls }} font-size-11 px-2 py-1">{{ $statusLabel }}</span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('beasiswa.show', $item->id) }}" class="btn btn-sm btn-outline-primary py-0 px-2 font-size-11" title="Buka Detail">
                                                <i class="ri-eye-line me-1"></i>Detail
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            Belum ada pengajuan beasiswa terdaftar.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection