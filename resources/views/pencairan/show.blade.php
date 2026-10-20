@extends('layouts.app')

@section('title', 'Buku Riwayat Pencairan Beasiswa')
@section('page-title', 'Buku Riwayat Pencairan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pencairan.index') }}">Pencairan Dana</a></li>
    <li class="breadcrumb-item active">Buku Riwayat</li>
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <a href="{{ route('pencairan.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="ri-arrow-left-line me-1"></i>Kembali ke Daftar Pencairan
            </a>
            <a href="{{ route('pencairan.cetak-riwayat', $pengajuan->id) }}" target="_blank" class="btn btn-sm btn-primary">
                <i class="ri-printer-line me-1"></i>Cetak Riwayat Pencairan (PDF)
            </a>
        </div>
    </div>

    {{-- Kartu Info Santri & Realisasi --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-4 col-md-5">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-md me-3">
                            <span class="avatar-title bg-soft-primary text-primary rounded-circle font-size-24 fw-bold">
                                {{ strtoupper(substr($pengajuan->santri?->nama_lengkap ?? 'S', 0, 1)) }}
                            </span>
                        </div>
                        <div>
                            <h5 class="fw-bold text-dark mb-1">{{ $pengajuan->santri?->nama_lengkap ?? '-' }}</h5>
                            <span class="badge bg-light text-muted border font-size-12">NIS: {{ $pengajuan->nis }}</span>
                        </div>
                    </div>
                    <hr class="my-3">
                    <ul class="list-unstyled mb-0 font-size-13">
                        <li class="mb-2 d-flex justify-content-between">
                            <span class="text-muted">Asrama / Kamar:</span>
                            <strong class="text-dark">{{ $pengajuan->santri?->asrama ?? '-' }} / Kamar {{ $pengajuan->santri?->kamar ?? '-' }}</strong>
                        </li>
                        <li class="mb-2 d-flex justify-content-between">
                            <span class="text-muted">Lembaga:</span>
                            <strong class="text-dark">{{ $pengajuan->lembaga1?->nama_lembaga ?? '-' }}</strong>
                        </li>
                        <li class="mb-2 d-flex justify-content-between">
                            <span class="text-muted">Periode:</span>
                            <strong class="text-dark">{{ $pengajuan->periode?->nama_periode ?? '-' }}</strong>
                        </li>
                        <li class="d-flex justify-content-between">
                            <span class="text-muted">Wali Santri:</span>
                            <strong class="text-dark">{{ $pengajuan->santri?->nama_wali ?? '-' }} ({{ $pengajuan->santri?->hp_wali ?? '-' }})</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-xl-8 col-md-7">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0 fw-bold text-dark"><i class="ri-pie-chart-line me-1 text-primary"></i> Plafon vs Realisasi Dana Bantuan</h6>
                    <span class="badge bg-soft-success text-success font-size-12">{{ $pengajuan->progress_pencairan }}% Terealisasi</span>
                </div>
                <div class="card-body p-4">
                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <span class="text-muted font-size-12 text-uppercase">Total Hak Beasiswa</span>
                            <h4 class="fw-bold text-dark mt-1">Rp {{ number_format($pengajuan->total_bantuan, 0, ',', '.') }}</h4>
                        </div>
                        <div class="col-4">
                            <span class="text-muted font-size-12 text-uppercase">Total Sudah Cair</span>
                            <h4 class="fw-bold text-success mt-1">Rp {{ number_format($pengajuan->total_dicairkan, 0, ',', '.') }}</h4>
                        </div>
                        <div class="col-4">
                            <span class="text-muted font-size-12 text-uppercase">Sisa Belum Cair</span>
                            <h4 class="fw-bold text-danger mt-1">Rp {{ number_format($pengajuan->sisa_pencairan, 0, ',', '.') }}</h4>
                        </div>
                    </div>

                    {{-- Progress Bar per Komponen --}}
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle mb-0 font-size-12">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>Komponen</th>
                                    <th>Plafon Hak</th>
                                    <th>Terealisasi</th>
                                    <th>Sisa Plafon</th>
                                    <th style="width: 120px;">Progress</th>
                                    <th>Metode</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rincian as $key => $r)
                                    @php
                                        $sisa = max(0, $r['hak'] - $r['cair']);
                                        $pct = $r['hak'] > 0 ? min(100, round(($r['cair'] / $r['hak']) * 100)) : 0;
                                    @endphp
                                    <tr>
                                        <td><strong>{{ $r['nama'] }}</strong></td>
                                        <td class="text-end">Rp {{ number_format($r['hak'], 0, ',', '.') }}</td>
                                        <td class="text-end text-success fw-semibold">Rp {{ number_format($r['cair'], 0, ',', '.') }}</td>
                                        <td class="text-end {{ $sisa > 0 ? 'text-danger fw-bold' : 'text-muted' }}">Rp {{ number_format($sisa, 0, ',', '.') }}</td>
                                        <td>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar {{ $pct == 100 ? 'bg-success' : 'bg-primary' }}" style="width: {{ $pct }}%;"></div>
                                            </div>
                                            <small class="text-muted font-size-10">{{ $pct }}%</small>
                                        </td>
                                        <td class="text-center">
                                            @if($r['metode_default'] === 'transfer')
                                                <span class="badge bg-soft-primary text-primary font-size-11"><i class="ri-bank-card-line me-1"></i>Transfer</span>
                                            @else
                                                <span class="badge bg-soft-success text-success font-size-11"><i class="ri-hand-coin-line me-1"></i>Tunai Bertahap</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Buku Transaksi Riwayat Pencairan --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-transparent py-3 border-bottom d-flex justify-content-between align-items-center">
            <div>
                <h5 class="card-title mb-0 fw-bold text-dark"><i class="ri-history-line me-1 text-primary"></i> Riwayat Transaksi Pencairan Dana</h5>
                <small class="text-muted">Catatan setiap tahap penyerahan uang tunai atau bukti transfer bank.</small>
            </div>
            <a href="{{ route('pencairan.cetak-riwayat', $pengajuan->id) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                <i class="ri-printer-line me-1"></i>Cetak Lembar Riwayat (PDF)
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle mb-0 font-size-13">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40px;" class="text-center">No</th>
                            <th>Tanggal Cair</th>
                            <th>Komponen Bantuan</th>
                            <th>Keterangan / Tahap</th>
                            <th class="text-center">Metode</th>
                            <th class="text-end">Nominal</th>
                            <th>Penerima</th>
                            <th>Petugas / Waktu</th>
                            <th class="text-center" style="width: 140px;">Bukti & Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengajuan->pencairans as $idx => $p)
                            <tr>
                                <td class="text-center text-muted">{{ $idx + 1 }}</td>
                                <td class="fw-semibold">{{ \Carbon\Carbon::parse($p->tanggal_cair)->format('d/m/Y') }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $p->kategori_label }}</span></td>
                                <td>
                                    <strong>{{ $p->tahap_ke ?: '-' }}</strong>
                                    @if($p->nomor_referensi)
                                        <small class="text-muted d-block font-size-11">Ref: {{ $p->nomor_referensi }}</small>
                                    @endif
                                    @if($p->catatan)
                                        <small class="text-muted d-block fst-italic font-size-11">{{ $p->catatan }}</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($p->metode_pencairan === 'transfer')
                                        <span class="badge bg-primary font-size-11"><i class="ri-bank-card-line me-1"></i>Transfer Bank</span>
                                    @else
                                        <span class="badge bg-success font-size-11"><i class="ri-hand-coin-line me-1"></i>Tunai Langsung</span>
                                    @endif
                                </td>
                                <td class="text-end fw-bold text-success font-size-14">
                                    Rp {{ number_format($p->nominal, 0, ',', '.') }}
                                </td>
                                <td>{{ $p->nama_penerima }}</td>
                                <td>
                                    <small class="text-dark fw-medium d-block">{{ $p->user?->nama_lengkap ?? $p->user?->username ?? 'Admin' }}</small>
                                    <small class="text-muted font-size-11">{{ $p->created_at->format('d M Y H:i') }}</small>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('pencairan.kuitansi', $p->id) }}" target="_blank" class="btn btn-xs btn-outline-primary" title="Cetak Kuitansi Tanda Terima">
                                            <i class="ri-printer-line"></i> Kuitansi
                                        </a>
                                        @if($p->file_bukti)
                                            <a href="{{ asset('storage/' . $p->file_bukti) }}" target="_blank" class="btn btn-xs btn-outline-info" title="Lihat Bukti Struk/Resi">
                                                <i class="ri-attachment-line"></i> Bukti
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    <i class="ri-hand-coin-line font-size-24 d-block mb-1"></i>
                                    Belum ada transaksi pencairan dana yang dicatat untuk santri ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
