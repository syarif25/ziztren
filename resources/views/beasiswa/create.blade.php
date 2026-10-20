@extends('layouts.app')

@section('title', 'Tambah Pengajuan Beasiswa')

@push('styles')
    <link href="{{ asset("assets/libs/select2/css/select2.min.css") }}" rel="stylesheet" type="text/css" />
    <style>
        .wizard-progress {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            position: relative;
            padding: 0 40px;
        }
        .wizard-progress::before {
            content: '';
            position: absolute;
            top: 25px; /* center of the circle */
            left: 40px;
            right: 40px;
            height: 3px;
            background-color: #e9ecef;
            z-index: 1;
        }
        .wizard-step {
            position: relative;
            z-index: 2;
            text-align: center;
        }
        .wizard-step .step-circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: bold;
            margin: 0 auto 10px auto;
            border: 3px solid #fff;
            box-shadow: 0 0 0 3px #e9ecef;
            transition: all 0.3s ease;
        }
        .wizard-step .step-label {
            font-size: 14px;
            color: #6c757d;
            font-weight: 500;
        }
        
        /* Completed State */
        .wizard-step.completed .step-circle {
            background-color: #28a745;
            color: white;
            box-shadow: 0 0 0 3px #28a745;
        }
        .wizard-step.completed .step-label {
            color: #28a745;
        }
        
        /* Active line */
        .wizard-step.completed + .wizard-step::before {
             /* We'll handle line color dynamically via JS or just keep it simple */
        }
    </style>
@endpush

@section('page-title', 'Tambah Pengajuan Beasiswa')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('beasiswa.index') }}">Beasiswa</a></li>
    <li class="breadcrumb-item active">Tambah Pengajuan Beasiswa</li>
@endsection

@section('content')
<div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul class="mb-0">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        @if(session('error'))
                                            <div class="alert alert-danger">{{ session('error') }}</div>
                                        @endif

                                        <form action="{{ route('beasiswa.store') }}" method="POST" enctype="multipart/form-data">
                                            @csrf

                                            
                                        <!-- Wizard Progress Bar -->
                                        <div class="wizard-progress">
                                            <div class="wizard-step" id="wizard-step-1">
                                                <div class="step-circle"><i class="ri-user-line"></i></div>
                                                <div class="step-label">Data Pemohon</div>
                                            </div>
                                            <div class="wizard-step" id="wizard-step-2">
                                                <div class="step-circle"><i class="ri-file-text-line"></i></div>
                                                <div class="step-label">Dokumen</div>
                                            </div>
                                            <div class="wizard-step" id="wizard-step-3">
                                                <div class="step-circle"><i class="ri-money-dollar-circle-line"></i></div>
                                                <div class="step-label">Jumlah Bantuan</div>
                                            </div>
                                        </div>
                                        <hr class="mb-4">

                                        <!-- Nav tabs -->
                                            <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
                                                <li class="nav-item">
                                                    <a class="nav-link active" data-bs-toggle="tab" href="#tab-data-pemohon" role="tab">
                                                        <span class="d-none d-sm-block"><i class="ri-user-line me-1"></i> Data Pemohon</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-bs-toggle="tab" href="#tab-info-penunjang" role="tab">
                                                        <span class="d-none d-sm-block"><i class="ri-file-text-line me-1"></i> Informasi Penunjang & Dokumen</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" data-bs-toggle="tab" href="#tab-jumlah-bantuan" role="tab">
                                                        <span class="d-none d-sm-block"><i class="ri-money-dollar-circle-line me-1"></i> Jumlah Bantuan</span>
                                                    </a>
                                                </li>
                                            </ul>

                                            <!-- Tab panes -->
                                            <div class="tab-content p-3 text-muted">

                                                {{-- ===== TAB 1: DATA PEMOHON ===== --}}
                                                <div class="tab-pane active" id="tab-data-pemohon" role="tabpanel">
                                                    <div class="row mt-3">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label fw-semibold">Pilih Santri (NIS) <span class="text-danger">*</span></label>
                                                            <select name="nis" class="req-tab1" id="nis" class="form-select select2-ajax">
                                                                @if(old('nis'))
                                                                    <option value="{{ old('nis') }}" selected>{{ old('nis') }}</option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <h5 class="font-size-14 mt-2 mb-3 text-muted">
                                                        <i class="ri-information-line me-1"></i> Lengkapi Data Santri
                                                    </h5>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Nama Lengkap</label>
                                                            <input type="text" id="ro_nama" class="form-control bg-light" disabled value="(Pilih santri terlebih dahulu)">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Jenis Kelamin</label>
                                                            <select name="jenis_kelamin" id="ro_jk" class="form-select req-tab1">
                                                                <option value="">-- Pilih Jenis Kelamin --</option>
                                                                <option value="L">Laki-laki (L)</option>
                                                                <option value="P">Perempuan (P)</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Tempat Lahir</label>
                                                            <input type="text" name="tempat_lahir" id="ro_tempat_lahir" class="form-control req-tab1" placeholder="Tempat Lahir">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Tanggal Lahir</label>
                                                            <input type="date" name="tanggal_lahir" id="ro_tanggal_lahir" class="form-control req-tab1">
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <label class="form-label">Anak Ke</label>
                                                            <input type="number" name="anak_ke" id="ro_anak_ke" class="form-control" placeholder="Anak Ke">
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <label class="form-label">Jml Saudara</label>
                                                            <input type="number" name="jumlah_saudara" id="ro_jumlah_saudara" class="form-control" placeholder="Jml Saudara">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Kamar</label>
                                                            <input type="text" name="kamar" id="ro_kamar" class="form-control req-tab1" placeholder="Kamar">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Nama Wali</label>
                                                            <input type="text" name="nama_wali" id="ro_wali" class="form-control req-tab1" placeholder="Nama Wali">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Pekerjaan Wali</label>
                                                            <input type="text" name="pekerjaan_wali" id="ro_pekerjaan" class="form-control req-tab1" placeholder="Pekerjaan Wali">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">No HP Wali</label>
                                                            <input type="text" name="no_hp_wali" id="ro_hp" class="form-control req-tab1" placeholder="No HP Wali">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Provinsi</label>
                                                            <select name="provinsi_id" id="ro_provinsi" class="form-select select2">
                                                                <option value="">-- Pilih Provinsi --</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Kabupaten/Kota</label>
                                                            <select name="kabupaten_id" id="ro_kabupaten" class="form-select select2" disabled>
                                                                <option value="">-- Pilih Kabupaten/Kota --</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Kecamatan</label>
                                                            <select name="kecamatan_id" id="ro_kecamatan" class="form-select select2" disabled>
                                                                <option value="">-- Pilih Kecamatan --</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Desa/Kelurahan</label>
                                                            <select name="desa_id" id="ro_desa" class="form-select select2" disabled>
                                                                <option value="">-- Pilih Desa/Kelurahan --</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-12 mb-3">
                                                            <label class="form-label">Jln/Dusun, RT/RW</label>
                                                            <textarea name="alamat_lengkap" id="ro_alamat" class="form-control req-tab1" rows="2" placeholder="Alamat Lengkap"></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- ===== TAB 2: INFORMASI PENUNJANG & DOKUMEN ===== --}}
                                                <div class="tab-pane" id="tab-info-penunjang" role="tabpanel">
                                                    <h5 class="font-size-14 mt-3 mb-3">Informasi Penunjang</h5>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Prestasi Kamar</label>
                                                            <textarea name="prestasi_kamar" class="form-control req-tab2" rows="3">{{ old('prestasi_kamar') }}</textarea>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Isian BHS</label>
                                                            <textarea name="isian_bhs" class="form-control req-tab2" rows="3">{{ old('isian_bhs') }}</textarea>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Kondisi Ekonomi</label>
                                                            <textarea name="kondisi_ekonomi" class="form-control req-tab2" rows="3">{{ old('kondisi_ekonomi') }}</textarea>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Prestasi Qiro'ati</label>
                                                            <textarea name="prestasi_qiroati" class="form-control req-tab2" rows="3">{{ old('prestasi_qiroati') }}</textarea>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Prestasi Madrasah</label>
                                                            <textarea name="prestasi_madrasah" class="form-control req-tab2" rows="3">{{ old('prestasi_madrasah') }}</textarea>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Prestasi Sekolah / PT</label>
                                                            <textarea name="prestasi_sekolah" class="form-control req-tab2" rows="3">{{ old('prestasi_sekolah') }}</textarea>
                                                        </div>
                                                    </div>

                                                    <hr class="my-4">

                                                    <h5 class="font-size-14 mb-3">
                                                        <i class="ri-attachment-line me-1"></i> Dokumen Lampiran
                                                        <small class="text-muted">(Format: PDF, Maks: 5MB)</small>
                                                    </h5>
                                                    <div class="row">
                                                        @php
                                                            $jenisDokumens = [
                                                                'Surat Perjanjian' => true,
                                                                'Kartu Keluarga' => true,
                                                                'SK Tidak Mampu Desa' => true,
                                                                'SK IKSASS' => true,
                                                                'Buku Harian Santri (BHS)' => true,
                                                                'SK Aktif Asrama' => true,
                                                                'Raport Qiroati Amsilati' => true,
                                                                'SK Aktif Madrasah, Sekolah, Qiroati' => true,
                                                                'Raport Madrasah' => true,
                                                                'Raport Sekolah PT' => true,
                                                                'Fhoto Rumah' => true,
                                                                'Fhoto Bukti Rekening PLN' => true,
                                                            ];
                                                        @endphp

                                                        @foreach($jenisDokumens as $jenis => $isRequired)
                                                            @php $fieldName = Str::slug($jenis, '_'); @endphp
                                                            <div class="col-md-6 mb-3">
                                                                <div class="d-flex justify-content-between align-items-end mb-1">
                                                                    <label class="form-label mb-0">{{ $jenis }} {!! !$isRequired ? '<small class="text-muted fw-normal">- Opsional</small>' : '<span class="text-danger">*</span>' !!}</label>
                                                                    <a href="javascript:void(0)" class="text-primary d-none btn-preview" style="font-size: 13px;">
                                                                        <i class="ri-eye-line align-bottom"></i> Preview
                                                                    </a>
                                                                </div>
                                                                <input type="file" name="dokumen[{{ $jenis }}]" class="form-control {{ $isRequired ? 'req-tab2' : '' }}" accept=".pdf">
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                {{-- ===== TAB 3: JUMLAH BANTUAN ===== --}}
                                                <div class="tab-pane" id="tab-jumlah-bantuan" role="tabpanel">
                                                    <div class="row mt-3">
                                                        <input type="hidden" name="periode_id" value="{{ $periode->id }}">
                                                        <div class="col-md-12 mb-3">
                                                            <label class="form-label fw-semibold">Periode Pendaftaran</label>
                                                            <input type="text" class="form-control bg-light fw-bold text-primary" value="{{ $periode->tahun_masehi }} — Gelombang {{ $periode->gelombang }}" readonly>
                                                        </div>
                                                        
                                                        <!-- Kolom Kiri: Lembaga -->
                                                        <div class="col-md-6">
                                                            <div class="card border border-light shadow-none">
                                                                <div class="card-body bg-light bg-opacity-50">
                                                                    <h5 class="font-size-14 mb-3 border-bottom pb-2">Informasi Lembaga</h5>
                                                                    <div class="row">
                                                                        <div class="col-md-7 mb-3">
                                                                            <label class="form-label fw-semibold">Qiro'ati / Amsilati</label>
                                                                            <select name="lembaga_qiroati_id" class="form-select select2">
                                                                                <option value="">-- Pilih Lembaga --</option>
                                                                                @foreach($lembagas as $lembaga)
                                                                                    <option value="{{ $lembaga->id }}" {{ old('lembaga_qiroati_id') == $lembaga->id ? 'selected' : '' }}>
                                                                                        {{ $lembaga->nama_lembaga }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-5 mb-3">
                                                                            <label class="form-label fw-semibold">Kelas/Smt</label>
                                                                            <input type="text" name="kelas_qiroati" class="form-control" value="{{ old('kelas_qiroati') }}" placeholder="Contoh: 3 / Smt 1">
                                                                        </div>

                                                                        <div class="col-md-7 mb-3">
                                                                            <label class="form-label fw-semibold">Madrasah <span class="text-danger">*</span></label>
                                                                            <select name="lembaga_1_id" class="form-select select2 req-tab3">
                                                                                <option value="">-- Pilih Madrasah --</option>
                                                                                @foreach($lembagas as $lembaga)
                                                                                    <option value="{{ $lembaga->id }}" {{ old('lembaga_1_id') == $lembaga->id ? 'selected' : '' }}>
                                                                                        {{ $lembaga->nama_lembaga }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-5 mb-3">
                                                                            <label class="form-label fw-semibold">Kelas <span class="text-danger">*</span></label>
                                                                            <input type="text" name="kelas_pendidikan_1" class="form-control req-tab3" placeholder="Contoh: X" value="{{ old('kelas_pendidikan_1') }}">
                                                                        </div>

                                                                        <div class="col-md-7 mb-3">
                                                                            <label class="form-label">Sekolah/PT <small class="text-muted">- Ops</small></label>
                                                                            <select name="lembaga_2_id" class="form-select select2 req-tab3">
                                                                                <option value="">-- Pilih Sekolah/PT --</option>
                                                                                @foreach($lembagas as $lembaga)
                                                                                    <option value="{{ $lembaga->id }}" {{ old('lembaga_2_id') == $lembaga->id ? 'selected' : '' }}>
                                                                                        {{ $lembaga->nama_lembaga }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-md-5 mb-3">
                                                                            <label class="form-label">Kelas <small class="text-muted">- Ops</small></label>
                                                                            <input type="text" name="kelas_pendidikan_2" class="form-control req-tab3" placeholder="Contoh: XI" value="{{ old('kelas_pendidikan_2') }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Kolom Kanan: Nominal -->
                                                        <div class="col-md-6">
                                                            <div class="card border border-light shadow-none">
                                                                <div class="card-body bg-light bg-opacity-50">
                                                                    <h5 class="font-size-14 mb-3 border-bottom pb-2">Rincian Nominal Bantuan</h5>
                                                                    <div class="row">
                                                                        <div class="col-md-12 mb-3">
                                                                            <label class="form-label fw-semibold">UTAP (Rp) <span class="text-danger">*</span></label>
                                                                            <input type="text" name="jumlah_utap" class="form-control req-tab3 format-rupiah" placeholder="0" value="{{ old('jumlah_utap') }}">
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <label class="form-label fw-semibold">SPP Gasal (Rp) <span class="text-danger">*</span></label>
                                                                            <input type="text" name="spp_gasal" class="form-control req-tab3 format-rupiah" placeholder="0" value="{{ old('spp_gasal') }}">
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <label class="form-label fw-semibold">SPP Genap (Rp) <span class="text-danger">*</span></label>
                                                                            <input type="text" name="spp_genap" class="form-control req-tab3 format-rupiah" placeholder="0" value="{{ old('spp_genap') }}">
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <label class="form-label fw-semibold">Living Kost (Rp) <span class="text-danger">*</span></label>
                                                                            <input type="text" name="living_cost" class="form-control req-tab3 format-rupiah" placeholder="0" value="{{ old('living_cost') }}">
                                                                        </div>
                                                                        <div class="col-md-6 mb-3">
                                                                            <label class="form-label fw-semibold">Biaya Lain (Rp) <span class="text-danger">*</span></label>
                                                                            <input type="text" name="biaya_lain" class="form-control req-tab3 format-rupiah" placeholder="0" value="{{ old('biaya_lain') }}">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Bagian Pengesahan Tanda Tangan -->
                                                        <div class="col-12 mt-3">
                                                            <div class="card border border-light shadow-none">
                                                                <div class="card-body bg-light bg-opacity-50">
                                                                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
                                                                        <h5 class="font-size-14 mb-0">
                                                                            <i class="ri-quill-pen-line me-1 text-primary"></i> Pengesahan Tanda Tangan Pemohon & Wali
                                                                        </h5>
                                                                        <span class="badge bg-soft-primary text-primary">Sentuh / Klik kotak untuk tanda tangan</span>
                                                                    </div>
                                                                    <div class="row g-3">
                                                                        <!-- TTD Wali / Ka. Kamar -->
                                                                        <div class="col-md-6">
                                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                                <label class="form-label fw-semibold mb-0">Tanda Tangan Wali / Ka. Kamar</label>
                                                                                <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 d-none" id="btn-reset-wali">
                                                                                    <i class="ri-delete-bin-line me-1"></i>Hapus
                                                                                </button>
                                                                            </div>

                                                                            <div class="border rounded bg-white text-center p-3 position-relative signature-clickable-box" id="box-preview-wali" style="min-height: 150px; cursor: pointer; border-style: dashed !important; border-width: 2px !important; border-color: #94a3b8 !important;" onclick="openSignatureModal('wali')">
                                                                                <div id="wali-placeholder" class="py-4 text-muted">
                                                                                    <div class="avatar-xs mx-auto mb-2">
                                                                                        <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                                                            <i class="ri-edit-2-line font-size-18"></i>
                                                                                        </span>
                                                                                    </div>
                                                                                    <span class="fw-bold font-size-13 d-block text-dark">Klik / Sentuh untuk Tanda Tangan Wali</span>
                                                                                    <small class="text-muted font-size-11">Membuka kanvas tanda tangan layar modal</small>
                                                                                </div>
                                                                                <div id="wali-signed" class="d-none">
                                                                                    <img id="img-preview-wali" src="" style="max-height: 100px; max-width: 100%; object-fit: contain;" alt="TTD Wali">
                                                                                    <div class="text-success font-size-11 mt-2"><i class="ri-check-line me-1"></i>Tanda tangan terpasang (Klik untuk ubah)</div>
                                                                                </div>
                                                                            </div>
                                                                            <input type="hidden" name="tanda_tangan_wali_base64" id="tanda_tangan_wali_base64">
                                                                            <small class="text-muted font-size-11 d-block mt-1">Sentuh kotak di atas untuk membuka popup tanda tangan.</small>
                                                                        </div>

                                                                        <!-- TTD Santri / Pemohon -->
                                                                        <div class="col-md-6">
                                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                                <label class="form-label fw-semibold mb-0">Tanda Tangan Santri (Pemohon)</label>
                                                                                <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 d-none" id="btn-reset-pemohon">
                                                                                    <i class="ri-delete-bin-line me-1"></i>Hapus
                                                                                </button>
                                                                            </div>

                                                                            <div class="border rounded bg-white text-center p-3 position-relative signature-clickable-box" id="box-preview-pemohon" style="min-height: 150px; cursor: pointer; border-style: dashed !important; border-width: 2px !important; border-color: #94a3b8 !important;" onclick="openSignatureModal('pemohon')">
                                                                                <div id="pemohon-placeholder" class="py-4 text-muted">
                                                                                    <div class="avatar-xs mx-auto mb-2">
                                                                                        <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                                                            <i class="ri-edit-2-line font-size-18"></i>
                                                                                        </span>
                                                                                    </div>
                                                                                    <span class="fw-bold font-size-13 d-block text-dark">Klik / Sentuh untuk Tanda Tangan Santri</span>
                                                                                    <small class="text-muted font-size-11">Membuka kanvas tanda tangan layar modal</small>
                                                                                </div>
                                                                                <div id="pemohon-signed" class="d-none">
                                                                                    <img id="img-preview-pemohon" src="" style="max-height: 100px; max-width: 100%; object-fit: contain;" alt="TTD Pemohon">
                                                                                    <div class="text-success font-size-11 mt-2"><i class="ri-check-line me-1"></i>Tanda tangan terpasang (Klik untuk ubah)</div>
                                                                                </div>
                                                                            </div>
                                                                            <input type="hidden" name="tanda_tangan_base64" id="tanda_tangan_base64">
                                                                            <small class="text-muted font-size-11 d-block mt-1">Sentuh kotak di atas untuk membuka popup tanda tangan.</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                            <!-- End Tab Content -->

                                            <div class="mt-4 d-flex justify-content-end gap-2">
                                                <a href="{{ route('beasiswa.index') }}" class="btn btn-secondary waves-effect">
                                                    <i class="ri-arrow-left-line me-1"></i> Batal
                                                </a>
                                                <button type="button" class="btn btn-info waves-effect waves-light d-none" id="btn-prev">
                                                    <i class="ri-arrow-left-s-line me-1"></i> Sebelumnya
                                                </button>
                                                <button type="button" class="btn btn-info waves-effect waves-light" id="btn-next">
                                                    Selanjutnya <i class="ri-arrow-right-s-line ms-1"></i>
                                                </button>
                                                <button type="submit" name="action" value="draft" class="btn btn-warning waves-effect waves-light">
                                                    <i class="ri-save-2-line me-1"></i> Simpan Draf
                                                </button>
                                                <button type="submit" name="action" value="submit" class="btn btn-primary waves-effect waves-light d-none btn-submit" id="btn-simpan">
                                                    <i class="ri-save-line me-1"></i> Simpan Pengajuan
                                                </button>
                                            </div>

                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
@endsection

@section('modals')
    <!-- Preview Modal -->
    <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-light py-2">
                    <h5 class="modal-title font-size-15" id="previewModalLabel">Preview Dokumen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" style="height: 80vh;">
                    <iframe src="" style="width: 100%; height: 100%; border: none;"></iframe>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Signature Pad -->
    <div class="modal fade" id="modalSignature" tabindex="-1" aria-labelledby="modalSignatureLabel" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-lg border-0">
                <div class="modal-header bg-light py-3">
                    <div class="d-flex align-items-center">
                        <div class="avatar-xs me-2">
                            <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                                <i class="ri-quill-pen-line font-size-18"></i>
                            </span>
                        </div>
                        <div>
                            <h5 class="modal-title font-size-15 mb-0" id="modalSignatureLabel">Goreskan Tanda Tangan</h5>
                            <small class="text-muted font-size-12" id="modalSignatureSubtitle">Goreskan tanda tangan dengan rapi menggunakan jari atau stylus</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3 p-md-4">
                    <div class="alert alert-info alert-dismissible fade show p-2 mb-3 font-size-12 d-flex align-items-center" role="alert">
                        <i class="ri-information-line font-size-16 me-2"></i>
                        
                    </div>

                    <!-- Canvas Wrapper -->
                    <div class="position-relative rounded border shadow-sm bg-white" style="touch-action: none; overflow: hidden; height: 280px;">
                        <canvas id="modal-signature-canvas" style="width: 100%; height: 100%; display: block; cursor: crosshair; touch-action: none;"></canvas>
                        
                        <div id="modal-signature-placeholder" class="position-absolute top-50 start-50 translate-middle text-muted opacity-50 text-center" style="pointer-events: none; width: 85%;">
                            <i class="ri-edit-2-line font-size-24 d-block mb-1 text-primary"></i>
                            <span class="fw-semibold font-size-14 text-dark d-block">Goreskan tanda tangan di sini</span>
                            <small class="text-muted font-size-12">Sentuh atau gerakkan kursor untuk mulai tanda tangan</small>
                        </div>

                        <!-- Garis batas tanda tangan -->
                        <div class="position-absolute bottom-0 start-0 w-100 px-4 pb-3" style="pointer-events: none;">
                            <div style="border-bottom: 1.5px dashed #cbd5e1; width: 100%;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2 d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-danger waves-effect" id="btn-clear-modal-sig">
                        <i class="ri-eraser-line me-1"></i> Bersihkan Kanvas
                    </button>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="button" class="btn btn-primary waves-effect waves-light" id="btn-apply-modal-sig">
                            <i class="ri-check-line me-1"></i> Terapkan Tanda Tangan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
                // Format rupiah saat mengetik
                $('.format-rupiah').on('keyup', function() {
                    let val = $(this).val().replace(/[^,\d]/g, '').toString();
                    let split = val.split(',');
                    let sisa = split[0].length % 3;
                    let rupiah = split[0].substr(0, sisa);
                    let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                    if (ribuan) {
                        let separator = sisa ? '.' : '';
                        rupiah += separator + ribuan.join('.');
                    }

                    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                    $(this).val(rupiah);
                });

                // Inisialisasi value jika ada old() data
                $('.format-rupiah').each(function() {
                    let val = $(this).val();
                    if(val) {
                        $(this).trigger('keyup');
                    }
                });

                // Init Select2 for Lembaga
                $('.select2').select2({
                    width: '100%',
                    placeholder: '-- Pilih --',
                    allowClear: true
                });

                // Init Select2 with AJAX for Santri
                $('#nis').select2({
                    width: '100%',
                    placeholder: '-- Ketik NIS atau Nama Santri --',
                    allowClear: true,
                    ajax: {
                        url: "{{ route('beasiswa.search-santri') }}",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                q: params.term // search term
                            };
                        },
                        processResults: function (data) {
                            return {
                                results: data
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 1
                });

                // AJAX Fetch Data Santri saat NIS dipilih
                $('#nis').on('change', function() {
                    var nis = $(this).val();
                    if (nis) {
                        $.ajax({
                            url: '/beasiswa/santri/' + nis,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                if (data) {
                                    $('#ro_nama').val(data.nama_lengkap);
                                    $('#ro_jk').val(data.jenis_kelamin || '');
                                    $('#ro_tempat_lahir').val(data.tempat_lahir || '');
                                    $('#ro_tanggal_lahir').val(data.tanggal_lahir || '');
                                    $('#ro_anak_ke').val(data.anak_ke || '');
                                    $('#ro_jumlah_saudara').val(data.jumlah_saudara || '');
                                    $('#ro_kamar').val(data.kamar || '');
                                    $('#ro_wali').val(data.nama_wali || '');
                                    $('#ro_pekerjaan').val(data.pekerjaan_wali || '');
                                    $('#ro_hp').val(data.no_hp_wali || '');
                                    $('#ro_alamat').val(data.alamat_lengkap || '');
                                    
                                    // Region auto-fill logic
                                    if(data.provinsi_id) {
                                        $('#ro_provinsi').val(data.provinsi_id).trigger('change');
                                        setTimeout(function() {
                                            if(data.kabupaten_id) {
                                                $('#ro_kabupaten').val(data.kabupaten_id).trigger('change');
                                                setTimeout(function() {
                                                    if(data.kecamatan_id) {
                                                        $('#ro_kecamatan').val(data.kecamatan_id).trigger('change');
                                                        setTimeout(function() {
                                                            if(data.desa_id) {
                                                                $('#ro_desa').val(data.desa_id).trigger('change');
                                                            }
                                                        }, 1000);
                                                    }
                                                }, 1000);
                                            }
                                        }, 1000);
                                    }
                                } else {
                                    $('#ro_nama').val('(Tidak ditemukan)');
                                }
                                checkTabs();
                            },
                            error: function() {
                                $('#ro_nama').val('(Error memuat data)');
                                checkTabs();
                            }
                        });
                    } else {
                        $('#ro_nama').val('(Pilih santri terlebih dahulu)');
                        checkTabs();
                    }
                });
                
                // Region Dropdowns Logic
                function loadProvinsi(selectedValue = null) {
                    $.ajax({
                        url: '/api/regions/provinsi',
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            var html = '<option value="">-- Pilih Provinsi --</option>';
                            $.each(data, function(key, val) {
                                html += '<option value="'+val.id+'">'+val.name+'</option>';
                            });
                            $('#ro_provinsi').html(html);
                            if(selectedValue) {
                                $('#ro_provinsi').val(selectedValue).trigger('change');
                            }
                        }
                    });
                }

                $('#ro_provinsi').on('change', function() {
                    var id = $(this).val();
                    if(id) {
                        $('#ro_kabupaten').prop('disabled', false);
                        $.ajax({
                            url: '/api/regions/kota/'+id,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                var html = '<option value="">-- Pilih Kabupaten/Kota --</option>';
                                $.each(data, function(key, val) {
                                    html += '<option value="'+val.id+'">'+val.name+'</option>';
                                });
                                // Keep the current value if it's in the options (for edit/autofill)
                                var currentVal = $('#ro_kabupaten').data('init-val');
                                $('#ro_kabupaten').html(html);
                                if (currentVal) {
                                    $('#ro_kabupaten').val(currentVal).trigger('change');
                                    $('#ro_kabupaten').data('init-val', null); // clear after use
                                }
                            }
                        });
                    } else {
                        $('#ro_kabupaten').html('<option value="">-- Pilih Kabupaten/Kota --</option>').prop('disabled', true);
                        $('#ro_kecamatan').html('<option value="">-- Pilih Kecamatan --</option>').prop('disabled', true);
                        $('#ro_desa').html('<option value="">-- Pilih Desa/Kelurahan --</option>').prop('disabled', true);
                    }
                });

                $('#ro_kabupaten').on('change', function() {
                    var id = $(this).val();
                    if(id) {
                        $('#ro_kecamatan').prop('disabled', false);
                        $.ajax({
                            url: '/api/regions/kecamatan/'+id,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                var html = '<option value="">-- Pilih Kecamatan --</option>';
                                $.each(data, function(key, val) {
                                    html += '<option value="'+val.id+'">'+val.name+'</option>';
                                });
                                var currentVal = $('#ro_kecamatan').data('init-val');
                                $('#ro_kecamatan').html(html);
                                if (currentVal) {
                                    $('#ro_kecamatan').val(currentVal).trigger('change');
                                    $('#ro_kecamatan').data('init-val', null); // clear after use
                                }
                            }
                        });
                    } else {
                        $('#ro_kecamatan').html('<option value="">-- Pilih Kecamatan --</option>').prop('disabled', true);
                        $('#ro_desa').html('<option value="">-- Pilih Desa/Kelurahan --</option>').prop('disabled', true);
                    }
                });

                $('#ro_kecamatan').on('change', function() {
                    var id = $(this).val();
                    if(id) {
                        $('#ro_desa').prop('disabled', false);
                        $.ajax({
                            url: '/api/regions/desa/'+id,
                            type: 'GET',
                            dataType: 'json',
                            success: function(data) {
                                var html = '<option value="">-- Pilih Desa/Kelurahan --</option>';
                                $.each(data, function(key, val) {
                                    html += '<option value="'+val.id+'">'+val.name+'</option>';
                                });
                                var currentVal = $('#ro_desa').data('init-val');
                                $('#ro_desa').html(html);
                                if (currentVal) {
                                    $('#ro_desa').val(currentVal).trigger('change');
                                    $('#ro_desa').data('init-val', null); // clear after use
                                }
                            }
                        });
                    } else {
                        $('#ro_desa').html('<option value="">-- Pilih Desa/Kelurahan --</option>').prop('disabled', true);
                    }
                });

                loadProvinsi();

                // Live Validation Function
                var allFormComplete = false;

                function checkTabs() {
                    // Check Tab 1
                    var tab1_complete = true;
                    $('.req-tab1').each(function() {
                        if ($(this).val() === '' || $(this).val() === null) {
                            tab1_complete = false;
                        }
                    });
                    if (tab1_complete) {
                        $('#wizard-step-1').addClass('completed');
                        $('#wizard-step-1 .step-circle').html('<i class="ri-check-line"></i>');
                    } else {
                        $('#wizard-step-1').removeClass('completed');
                        $('#wizard-step-1 .step-circle').html('<i class="ri-user-line"></i>');
                    }

                    // Check Tab 3
                    var tab3_complete = true;
                    $('.req-tab3').each(function() {
                        if ($(this).val() === '' || $(this).val() === null) {
                            tab3_complete = false;
                        }
                    });
                    if (tab3_complete) {
                        $('#wizard-step-3').addClass('completed');
                        $('#wizard-step-3 .step-circle').html('<i class="ri-check-line"></i>');
                    } else {
                        $('#wizard-step-3').removeClass('completed');
                        $('#wizard-step-3 .step-circle').html('<i class="ri-money-dollar-circle-line"></i>');
                    }

                    // Check Tab 2 - text/textarea fields
                    var tab2_text_complete = true;
                    $('.req-tab2:not([type="file"])').each(function() {
                        if ($(this).val() === '' || $(this).val() === null) {
                            tab2_text_complete = false;
                        }
                    });

                    // Check Tab 2 - required file inputs
                    var tab2_files_complete = true;
                    $('.req-tab2[type="file"]').each(function() {
                        // If the file input has no files selected AND there's no existing doc marker
                        if (this.files.length === 0) {
                            tab2_files_complete = false;
                        }
                    });

                    var tab2_complete = tab2_text_complete && tab2_files_complete;

                    if (tab2_complete) {
                        $('#wizard-step-2').addClass('completed');
                        $('#wizard-step-2 .step-circle').html('<i class="ri-check-line"></i>');
                    } else {
                        $('#wizard-step-2').removeClass('completed');
                        $('#wizard-step-2 .step-circle').html('<i class="ri-file-text-line"></i>');
                    }

                    // All tabs must be complete before submit is enabled
                    allFormComplete = tab1_complete && tab2_complete && tab3_complete;
                    updateSubmitButton();
                }

                function updateSubmitButton() {
                    if (allFormComplete) {
                        $('#btn-simpan').removeClass('disabled').prop('disabled', false)
                            .removeClass('btn-secondary').addClass('btn-primary');
                    } else {
                        $('#btn-simpan').addClass('disabled').prop('disabled', true)
                            .removeClass('btn-primary').addClass('btn-secondary');
                    }
                }

                // Trigger check on any input change
                $('input, select, textarea').on('input change', function() {
                    checkTabs();
                });
                
                // Also trigger check after Select2 changes
                $('.select2, .select2-ajax').on('select2:select select2:unselect select2:clear', function() {
                    checkTabs();
                });
                
                // Highlight file inputs that have files
                $('input[type="file"]').on('change', function() {
                    var previewBtn = $(this).closest('.mb-3').find('.btn-preview');
                    if ($(this).val() && this.files[0]) {
                        $(this).addClass('bg-success bg-opacity-10 border-success');
                        previewBtn.removeClass('d-none');
                        
                        var fileUrl = URL.createObjectURL(this.files[0]);
                        previewBtn.off('click').on('click', function() {
                            $('#previewModal iframe').attr('src', fileUrl);
                            $('#previewModal').modal('show');
                        });
                    } else {
                        $(this).removeClass('bg-success bg-opacity-10 border-success');
                        previewBtn.addClass('d-none');
                    }
                    // Re-run complete check when file input changes
                    checkTabs();
                });
                
                // Initial check on load
                setTimeout(checkTabs, 500);

                // Tab Navigation Logic
                var currentTab = 0;
                var tabs = ['#tab-data-pemohon', '#tab-info-penunjang', '#tab-jumlah-bantuan'];
                
                function updateButtons() {
                    $('#btn-prev').toggleClass('d-none', currentTab === 0);
                    $('#btn-next').toggleClass('d-none', currentTab === tabs.length - 1);
                    $('.btn-submit').toggleClass('d-none', currentTab !== tabs.length - 1);
                    $('button[value="draft"]').toggleClass('d-none', currentTab === tabs.length - 1);
                    updateSubmitButton();
                }

                // Bypass client-side validation when Draft is clicked
                $('button[value="draft"]').click(function() {
                    $('form').addClass('draft-mode');
                });
                
                $('form').submit(function(e) {
                    if ($(this).hasClass('draft-mode')) {
                        return true; // Bypass custom validation logic if any
                    }
                    // other validation...
                });

                $('#btn-next').click(function() {
                    if (currentTab < tabs.length - 1) {
                        currentTab++;
                        $('a[href="' + tabs[currentTab] + '"]').tab('show');
                    }
                });

                $('#btn-prev').click(function() {
                    if (currentTab > 0) {
                        currentTab--;
                        $('a[href="' + tabs[currentTab] + '"]').tab('show');
                    }
                });

                $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                    var target = $(e.target).attr("href");
                    currentTab = tabs.indexOf(target);
                    updateButtons();
                });
                
                updateButtons();

                // ===== MODAL SIGNATURE PAD LOGIC =====
                var modalCanvas = document.getElementById('modal-signature-canvas');
                var modalSigPad = null;
                var currentSigTarget = 'wali'; // 'wali' or 'pemohon'

                if (typeof SignaturePad !== 'undefined' && modalCanvas) {
                    modalSigPad = new SignaturePad(modalCanvas, {
                        backgroundColor: 'rgba(255, 255, 255, 0)',
                        penColor: '#002f6c',
                        minWidth: 1.5,
                        maxWidth: 3.5,
                    });

                    modalSigPad.addEventListener("beginStroke", function() {
                        $('#modal-signature-placeholder').fadeOut(150);
                    });
                }

                function resizeModalCanvas() {
                    if (!modalCanvas || !modalSigPad) return;
                    var ratio = Math.max(window.devicePixelRatio || 1, 1);
                    var rect = modalCanvas.getBoundingClientRect();

                    var oldContent = !modalSigPad.isEmpty() ? modalSigPad.toDataURL() : null;

                    modalCanvas.width = rect.width * ratio;
                    modalCanvas.height = rect.height * ratio;
                    modalCanvas.getContext("2d").scale(ratio, ratio);

                    modalSigPad.clear();
                    if (oldContent) {
                        modalSigPad.fromDataURL(oldContent);
                        $('#modal-signature-placeholder').hide();
                    } else {
                        $('#modal-signature-placeholder').show();
                    }
                }

                $('#modalSignature').on('shown.bs.modal', function () {
                    resizeModalCanvas();
                });

                $(window).on('resize orientationchange', function() {
                    if ($('#modalSignature').hasClass('show')) {
                        resizeModalCanvas();
                    }
                });

                // Global function to trigger signature modal
                window.openSignatureModal = function(target) {
                    currentSigTarget = target;
                    if (target === 'wali') {
                        $('#modalSignatureLabel').text('Tanda Tangan Wali / Ka. Kamar');
                        $('#modalSignatureSubtitle').text('Goreskan tanda tangan Wali atau Kepala Kamar menggunakan jari atau stylus');
                    } else {
                        $('#modalSignatureLabel').text('Tanda Tangan Santri (Pemohon)');
                        $('#modalSignatureSubtitle').text('Goreskan tanda tangan Santri Pemohon menggunakan jari atau stylus');
                    }

                    if (modalSigPad) {
                        modalSigPad.clear();
                    }
                    $('#modal-signature-placeholder').show();

                    var modal = new bootstrap.Modal(document.getElementById('modalSignature'));
                    modal.show();
                };

                // Clear button inside modal
                $('#btn-clear-modal-sig').on('click', function() {
                    if (modalSigPad) {
                        modalSigPad.clear();
                        $('#modal-signature-placeholder').fadeIn(150);
                    }
                });

                // Apply button inside modal
                $('#btn-apply-modal-sig').on('click', function() {
                    if (!modalSigPad || modalSigPad.isEmpty()) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Kanvas Masih Kosong',
                            text: 'Silakan goreskan tanda tangan terlebih dahulu sebelum menerapkan.',
                            confirmButtonColor: '#0f9cf3'
                        });
                        return;
                    }

                    var dataUrl = modalSigPad.toDataURL('image/png');

                    if (currentSigTarget === 'wali') {
                        $('#tanda_tangan_wali_base64').val(dataUrl);
                        $('#img-preview-wali').attr('src', dataUrl);
                        $('#wali-placeholder').addClass('d-none');
                        $('#wali-signed').removeClass('d-none');
                        $('#btn-reset-wali').removeClass('d-none');
                    } else {
                        $('#tanda_tangan_base64').val(dataUrl);
                        $('#img-preview-pemohon').attr('src', dataUrl);
                        $('#pemohon-placeholder').addClass('d-none');
                        $('#pemohon-signed').removeClass('d-none');
                        $('#btn-reset-pemohon').removeClass('d-none');
                    }

                    var modalEl = document.getElementById('modalSignature');
                    var modalInstance = bootstrap.Modal.getInstance(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Tanda tangan berhasil diterapkan!',
                        showConfirmButton: false,
                        timer: 2000
                    });
                });

                // Reset TTD Wali button on form card
                $('#btn-reset-wali').on('click', function(e) {
                    e.stopPropagation();
                    $('#tanda_tangan_wali_base64').val('');
                    $('#img-preview-wali').attr('src', '');
                    $('#wali-signed').addClass('d-none');
                    $('#wali-placeholder').removeClass('d-none');
                    $(this).addClass('d-none');
                });

                // Reset TTD Pemohon button on form card
                $('#btn-reset-pemohon').on('click', function(e) {
                    e.stopPropagation();
                    $('#tanda_tangan_base64').val('');
                    $('#img-preview-pemohon').attr('src', '');
                    $('#pemohon-signed').addClass('d-none');
                    $('#pemohon-placeholder').removeClass('d-none');
                    $(this).addClass('d-none');
                });

            });
    </script>
@endpush
