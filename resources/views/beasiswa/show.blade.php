@extends('layouts.app')

@section('title', 'Detail Pengajuan')

@push('styles')
    <style>
        .step-icon { width:38px;height:38px;flex-shrink:0;border-radius:50%;display:flex;align-items:center;justify-content:center; }
        .info-label { font-size:0.78rem;color:#6c757d;font-weight:500;text-transform:uppercase;letter-spacing:0.5px; }
    </style>
@endpush

@section('page-title', 'Detail Pengajuan Beasiswa')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('beasiswa.index') }}">Beasiswa</a></li>
    <li class="breadcrumb-item active">Detail Pengajuan</li>
@endsection

@section('content')
    <div class="row">

        <div class="col-xl-8">

            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0"><i class="ri-user-line me-1 text-primary"></i> Informasi Santri</h5>
                        @php
                            if (!function_exists('checkEmpty')) {
                                function checkEmpty($value, $isCurrency = false) {
                                    if (empty($value) || $value === '-' || $value === '0' || $value === 0) {
                                        $text = $isCurrency ? 'Rp 0' : '-';
                                        return '<span class="text-danger fw-bold">'.$text.'</span>';
                                    }
                                    if ($isCurrency) {
                                        return 'Rp ' . number_format($value, 0, ',', '.');
                                    }
                                    return $value;
                                }
                            }
                            $statusMap = [
                                'draft'     => ['label' => 'Draft',     'class' => 'bg-secondary'],
                                'proses'    => ['label' => 'Proses',    'class' => 'bg-info'],
                                'acc'       => ['label' => 'ACC',       'class' => 'bg-primary'],
                                'pencairan' => ['label' => 'Pencairan', 'class' => 'bg-success'],
                            ];
                            $currentStatus = strtolower($pengajuan->status_pengajuan ?? 'draft');
                            $badge = $statusMap[$currentStatus] ?? ['label' => ucfirst($currentStatus), 'class' => 'bg-warning'];
                        @endphp
                        <span class="badge {{ $badge['class'] }} px-3 py-2 font-size-12">{{ $badge['label'] }}</span>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="info-label">Nama Lengkap</small>
                            <p class="fw-bold mb-1">{!! checkEmpty($pengajuan->santri?->nama_lengkap) !!}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="info-label">NIS</small>
                            <p class="mb-1">{!! checkEmpty($pengajuan->santri?->nis) !!}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="info-label">Tempat / Tgl Lahir</small>
                            <p class="mb-1">
                                {!! checkEmpty($pengajuan->santri?->tempat_lahir) !!},
                                {!! checkEmpty($pengajuan->santri?->tanggal_lahir ? \Carbon\Carbon::parse($pengajuan->santri->tanggal_lahir)->translatedFormat('d F Y') : null) !!}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <small class="info-label">Jenis Kelamin</small>
                            <p class="mb-1">{!! checkEmpty($pengajuan->santri?->jenis_kelamin ? ($pengajuan->santri->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan') : null) !!}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="info-label">Anak Ke</small>
                            <p class="mb-1">
                                @if(empty($pengajuan->santri?->anak_ke) || empty($pengajuan->santri?->jumlah_saudara))
                                    <span class="text-danger fw-bold">-</span>
                                @else
                                    {{ $pengajuan->santri->anak_ke }} dari {{ $pengajuan->santri->jumlah_saudara }} bersaudara
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <small class="info-label">Asrama</small>
                            <p class="mb-1">{!! checkEmpty($pengajuan->santri?->kamar) !!}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="info-label">Nama Wali</small>
                            <p class="mb-1">{!! checkEmpty($pengajuan->santri?->nama_wali) !!}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="info-label">Pekerjaan Wali</small>
                            <p class="mb-1">{!! checkEmpty($pengajuan->santri?->pekerjaan_wali) !!}</p>
                        </div>
                        <div class="col-md-6">
                            <small class="info-label">No HP WA Wali</small>
                            <p class="mb-1">{!! checkEmpty($pengajuan->santri?->no_hp_wali) !!}</p>
                        </div>
                        <div class="col-12">
                            <small class="info-label">Alamat</small>
                            <p class="mb-0">
                                @if(empty($pengajuan->santri?->alamat_lengkap) || $pengajuan->santri?->alamat_lengkap == '-')
                                    <span class="text-danger fw-bold">-</span>
                                @else
                                    {{ $pengajuan->santri->alamat_lengkap }}, {{ $pengajuan->santri->desa?->name ?? '-' }}, {{ $pengajuan->santri->kecamatan?->name ?? '-' }}, {{ $pengajuan->santri->kabupaten?->name ?? '-' }}, {{ $pengajuan->santri->provinsi?->name ?? '-' }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="ri-book-open-line me-1 text-success"></i> Informasi Pendidikan</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted" style="width:35%">Periode</td>
                                    <td>{!! checkEmpty($pengajuan->periode?->nama_periode) !!}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Madrasah / Pesantren</td>
                                    <td>
                                        @if(empty($pengajuan->lembaga1?->nama_lembaga) || $pengajuan->lembaga1?->nama_lembaga == '-')
                                            <span class="text-danger fw-bold">-</span>
                                        @else
                                            {{ $pengajuan->lembaga1->nama_lembaga }} — Kelas {{ $pengajuan->kelas_pendidikan_1 ?? '-' }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Sekolah Formal</td>
                                    <td>
                                        @if(empty($pengajuan->lembaga2?->nama_lembaga) || $pengajuan->lembaga2?->nama_lembaga == '-')
                                            <span class="text-danger fw-bold">-</span>
                                        @else
                                            {{ $pengajuan->lembaga2->nama_lembaga }} — Kelas {{ $pengajuan->kelas_pendidikan_2 ?? '-' }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Lembaga Qiroati</td>
                                    <td>{!! checkEmpty($pengajuan->lembagaQiroati?->nama_lembaga) !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="ri-money-dollar-circle-line me-1 text-warning"></i> Rincian Bantuan</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted" style="width:35%">UTAP</td>
                                    <td>{!! checkEmpty($pengajuan->jumlah_utap, true) !!}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">SPP Gasal</td>
                                    <td>{!! checkEmpty($pengajuan->spp_gasal, true) !!}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">SPP Genap</td>
                                    <td>{!! checkEmpty($pengajuan->spp_genap, true) !!}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Living Cost</td>
                                    <td>{!! checkEmpty($pengajuan->living_cost, true) !!}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Biaya Lain-lain</td>
                                    <td>{!! checkEmpty($pengajuan->biaya_lain, true) !!}</td>
                                </tr>
                                <tr class="table-primary">
                                    <td class="fw-bold">Total Bantuan</td>
                                    @php
                                        $total = ($pengajuan->jumlah_utap ?? 0) + ($pengajuan->spp_gasal ?? 0) + ($pengajuan->spp_genap ?? 0) + ($pengajuan->living_cost ?? 0) + ($pengajuan->biaya_lain ?? 0);
                                    @endphp
                                    <td class="fw-bold">{!! checkEmpty($total, true) !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="ri-folder-line me-1 text-info"></i> Lampiran Dokumen</h5>
                    @if($pengajuan->dokumen && $pengajuan->dokumen->count() > 0)
                        <div class="row g-2">
                            @foreach($pengajuan->dokumen as $doc)
                            <div class="col-md-4">
                                <div class="border rounded p-2 d-flex align-items-center gap-2">
                                    <i class="ri-file-pdf-line font-size-24 text-danger"></i>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <p class="mb-0 text-truncate fw-medium">{{ $doc->jenis_dokumen }}</p>
                                        <small class="text-muted">{{ number_format($doc->file_size / 1024, 2) }} KB</small>
                                    </div>
                                    <a href="{{ route('dokumen.lihat', $doc->id) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Lihat Dokumen">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0"><i class="ri-information-line me-1"></i> Belum ada dokumen.</p>
                    @endif
                </div>
            </div>

        </div>

        <div class="col-xl-4">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="ri-settings-3-line me-1"></i> Aksi</h5>
                    <div class="d-grid gap-2">
                        <a href="{{ route('beasiswa.index') }}" class="btn btn-outline-secondary">
                            <i class="ri-arrow-left-line me-1"></i> Kembali ke Daftar
                        </a>
                        <a href="{{ route('beasiswa.edit', $pengajuan->id) }}" class="btn btn-outline-info">
                            <i class="ri-pencil-line me-1"></i> Edit Data Pengajuan
                        </a>
                        <a href="{{ route('beasiswa.pdf', $pengajuan->id) }}" target="_blank" class="btn btn-dark">
                            <i class="ri-printer-line me-1"></i> Cetak Formulir (PDF)
                        </a>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#statusModal">
                            <i class="ri-refresh-line me-1"></i> Update Status
                        </button>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-3"><i class="ri-route-line me-1"></i> Progres Usulan</h5>
                    @php
                        $steps = [
                            'draft'     => ['icon' => 'ri-edit-2-line',             'label' => 'Draft',     'desc' => 'Pengajuan dibuat'],
                            'proses'    => ['icon' => 'ri-loader-4-line',            'label' => 'Proses',    'desc' => 'Sedang diverifikasi'],
                            'acc'       => ['icon' => 'ri-check-double-line',        'label' => 'ACC',       'desc' => 'Disetujui'],
                            'pencairan' => ['icon' => 'ri-money-dollar-circle-line', 'label' => 'Pencairan', 'desc' => 'Dana dicairkan'],
                        ];
                        $statusOrder = array_keys($steps);
                        $currentIdx  = array_search($currentStatus, $statusOrder);
                    @endphp
                    <div class="d-flex flex-column gap-1">
                        @foreach($steps as $key => $step)
                            @php
                                $stepIdx   = array_search($key, $statusOrder);
                                $isDone    = $stepIdx < $currentIdx;
                                $isActive  = $stepIdx === $currentIdx;
                                $isPending = $stepIdx > $currentIdx;

                                $historiLogs = collect();
                                if ($key !== 'draft') {
                                    $historiLogs = $pengajuan->historiStatus?->filter(function($item) use ($key, $step) {
                                        $s = strtolower($item->status);
                                        return $s === strtolower($key) || $s === strtolower($step['label']) || ($key === 'proses' && $s === 'pending');
                                    })->sortBy('created_at');
                                }
                            @endphp
                            <div class="d-flex align-items-start gap-3 p-2 rounded {{ $isActive ? 'bg-light border border-primary' : '' }}">
                                <div class="step-icon mt-1" style="background:{{ $isDone ? '#0ab39c' : ($isActive ? '#405189' : '#e9ecef') }};color:{{ ($isDone || $isActive) ? '#fff' : '#adb5bd' }}">
                                    <i class="{{ $step['icon'] }}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="mb-0 fw-{{ $isActive ? 'bold' : 'normal' }} {{ $isPending ? 'text-muted' : '' }}">
                                        {{ $step['label'] }}
                                        @if($isActive)<span class="badge bg-primary ms-1" style="font-size:0.7rem;">Sekarang</span>@endif
                                    </p>
                                    @if($key === 'draft')
                                        <div class="text-muted mt-1" style="font-size:0.75rem;">
                                            <i class="ri-calendar-line align-middle"></i> {{ \Carbon\Carbon::parse($pengajuan->created_at)->translatedFormat('d M Y, H:i') }}
                                        </div>
                                    @else
                                        @foreach($historiLogs as $log)
                                            <div class="mt-2 p-2 bg-light border rounded text-dark">
                                                <div class="text-muted" style="font-size:0.7rem;">
                                                    <i class="ri-calendar-line align-middle"></i> {{ \Carbon\Carbon::parse($log->created_at)->translatedFormat('d M Y, H:i') }}
                                                </div>
                                                @if($log->catatan)
                                                <div class="mt-1" style="font-size:0.75rem; font-style: italic;">
                                                    <i class="ri-message-2-line align-middle me-1"></i> "{{ $log->catatan }}"
                                                </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            @if(!$loop->last)
                                <div style="width:2px;height:16px;background:{{ $isDone ? '#0ab39c' : '#e9ecef' }};margin-left:18px;"></div>
                            @endif
                        @endforeach
                    </div>
                    @if($pengajuan->tanggal_bantuan && $currentStatus === 'pencairan')
                    <hr>
                    <div class="text-center">
                        <small class="text-muted">Tanggal Pencairan</small>
                        <p class="fw-bold text-success mb-0"><i class="ri-calendar-check-line me-1"></i>{{ \Carbon\Carbon::parse($pengajuan->tanggal_bantuan)->translatedFormat('d F Y') }}</p>
                    </div>
                    @endif
                </div>
            </div>

        </div>

    </div>
@endsection

@section('modals')
    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="statusModalLabel">Update Status Pengajuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formUpdateStatus">
                        @csrf
                        <input type="hidden" name="pengajuan_id" value="{{ $pengajuan->id }}">
                        <div class="mb-3">
                            <label class="form-label">Status Baru <span class="text-danger">*</span></label>
                            <select class="form-select" name="status_pengajuan" id="selectStatus" required>
                                <option value="draft"     {{ $pengajuan->status_pengajuan == 'draft'     ? 'selected' : '' }}>Draft</option>
                                <option value="proses"    {{ $pengajuan->status_pengajuan == 'proses'    ? 'selected' : '' }}>Proses</option>
                                <option value="acc"       {{ $pengajuan->status_pengajuan == 'acc'       ? 'selected' : '' }}>ACC</option>
                                <option value="pencairan" {{ $pengajuan->status_pengajuan == 'pencairan' ? 'selected' : '' }}>Pencairan</option>
                            </select>
                        </div>
                        <div class="mb-3" id="tanggal_bantuan_container">
                            <label class="form-label">Tanggal</label>
                            <input type="date" class="form-control" name="tanggal_bantuan" id="tanggal_bantuan" value="{{ $pengajuan->tanggal_bantuan }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Catatan (Opsional)</label>
                            <input type="text" class="form-control" name="catatan" placeholder="Keterangan tambahan...">
                        </div>
                        <button type="submit" class="btn btn-primary w-100" id="btnSimpanStatus">
                            <i class="ri-save-line me-1"></i> Simpan Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            @if(session('success'))
                Swal.fire({ icon: 'success', title: 'Berhasil', text: '{{ session("success") }}', timer: 3000, showConfirmButton: false });
            @endif
            @if(session('error'))
                Swal.fire({ icon: 'error', title: 'Gagal', text: '{{ session("error") }}', timer: 3000, showConfirmButton: false });
            @endif

            $('#formUpdateStatus').on('submit', function(e) {
                e.preventDefault();
                var btn = $('#btnSimpanStatus');
                btn.html('<i class="bx bx-loader bx-spin me-1"></i> Menyimpan...').prop('disabled', true);
                $.ajax({
                    url: '/beasiswa/{{ $pengajuan->id }}/status',
                    type: 'POST',
                    headers: { 'Accept': 'application/json' },
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message, timer: 2000, showConfirmButton: false })
                                .then(function() { location.reload(); });
                        } else {
                            Swal.fire('Gagal', res.message || 'Terjadi kesalahan.', 'error');
                        }
                    },
                    error: function(xhr) {
                        var msg = 'Terjadi kesalahan sistem.';
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            msg = Object.values(xhr.responseJSON.errors)[0][0];
                        }
                        Swal.fire('Error', msg, 'error');
                    },
                    complete: function() { btn.html('<i class="ri-save-line me-1"></i> Simpan Status').prop('disabled', false); }
                });
            });
        });
    </script>
@endpush
