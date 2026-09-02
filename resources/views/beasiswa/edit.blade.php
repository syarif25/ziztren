<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Tambah Pengajuan Beasiswa | ZIZTREN</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="ZIZTREN Scholarship Management" name="description" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset("assets/images/favicon.ico") }}">

        <!-- Bootstrap Css -->
        <link href="{{ asset("assets/css/bootstrap.min.css") }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset("assets/css/icons.min.css") }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ asset("assets/css/app.min.css") }}" id="app-style" rel="stylesheet" type="text/css" />
        <!-- Select2 CSS -->
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

</head>

    <body data-topbar="dark">

        <!-- Begin page -->
        <div id="layout-wrapper">

            
            <header id="page-topbar">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box">
                            <a href="{{{ route('dashboard') }}}" class="logo logo-dark">
                                <span class="logo-sm">
                                    <span class="fw-bold text-primary" style="font-size: 20px;">Z</span>
                                </span>
                                <span class="logo-lg">
                                    <span class="fw-bold text-primary" style="font-size: 22px; letter-spacing: 1px;">ZIZTREN</span>
                                </span>
                            </a>

                            <a href="{{{ route('dashboard') }}}" class="logo logo-light">
                                <span class="logo-sm">
                                    <span class="fw-bold text-white" style="font-size: 20px;">Z</span>
                                </span>
                                <span class="logo-lg">
                                    <span class="fw-bold text-white" style="font-size: 22px; letter-spacing: 1px;">ZIZTREN</span>
                                </span>
                            </a>
                        </div>

                        <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                            <i class="ri-menu-2-line align-middle"></i>
                        </button>

                    </div>
                </div>
            </header>

            <!-- ========== Left Sidebar Start ========== -->
            <div class="vertical-menu">

                <div data-simplebar class="h-100">

                    <!-- User details -->
                    <div class="user-profile text-center mt-3">
                        <div class="">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username ?? 'User') }}&background=random&color=fff" alt="" class="avatar-md rounded-circle">
                        </div>
                        <div class="mt-3">
                            <h4 class="font-size-16 mb-1">{{ Auth::user()->username ?? 'User' }}</h4>
                            <span class="text-muted"><i class="ri-record-circle-line align-middle font-size-14 text-success"></i> Online</span>
                        </div>
                    </div>

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        <!-- Left Menu Start -->
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li class="menu-title">Menu</li>

                            <li>
                                <a href="{{{ route('dashboard') }}}" class="waves-effect">
                                    <i class="ri-dashboard-line"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>

                            <li class="mm-active">
                                <a href="{{{ route('beasiswa.index') }}}" class="waves-effect">
                                    <i class="ri-file-list-3-line"></i>
                                    <span>Beasiswa</span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-database-2-line"></i>
                                    <span>Master</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="false">
                                    <li><a href="{{ route('pengguna.index') }}">Pengguna</a></li>
                                    <li><a href="{{ route('lembaga.index') }}">Lembaga</a></li>
                                    <li><a href="{{ route('master-periode.index') }}">Master Periode</a></li>
                                    <li><a href="{{ route('provinsi.index') }}">Provinsi</a></li>
                                    <li><a href="{{ route('kota.index') }}">Kota</a></li>
                                    <li><a href="{{ route('kecamatan.index') }}">Kecamatan</a></li>
                                    <li><a href="{{ route('desa.index') }}">Desa/Kelurahan</a></li>
                                    <li><a href="{{ route('santri.index') }}">Santri</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="waves-effect text-danger">
                                    <i class="ri-shut-down-line text-danger"></i>
                                    <span>Logout</span>
                                </a>
                                <form id="logout-form" action="{{{ route('logout') }}}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                    <!-- Sidebar -->
                </div>
            </div>
            <!-- Left Sidebar End -->

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">
                        
                        <!-- start page title -->
                        <div class="row">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">Edit Pengajuan Beasiswa</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="{{ route('beasiswa.index') }}">Beasiswa</a></li>
                                            <li class="breadcrumb-item active">Edit Pengajuan</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

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

                                        <form action="{{ route('beasiswa.update', $pengajuan->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')

                                            
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
                                                            <label class="form-label fw-semibold">Santri (NIS) <span class="text-danger">*</span></label>
                                                            <input type="text" name="nis" class="form-control bg-light" id="nis" value="{{ $pengajuan->nis }}" readonly>
                                                            <small class="text-muted">NIS tidak dapat diubah (readonly).</small>
                                                        </div>
                                                    </div>

                                                    <h5 class="font-size-14 mt-2 mb-3 text-muted">
                                                        <i class="ri-information-line me-1"></i> Lengkapi Data Santri
                                                    </h5>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Nama Lengkap</label>
                                                            <input type="text" id="ro_nama" class="form-control bg-light" disabled value="{{ $pengajuan->santri ? $pengajuan->santri->nama_lengkap : '' }}">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Jenis Kelamin</label>
                                                            <select name="jenis_kelamin" id="ro_jk" class="form-select req-tab1">
                                                                <option value="">-- Pilih Jenis Kelamin --</option>
                                                                <option value="L" {{ ($pengajuan->santri && $pengajuan->santri->jenis_kelamin == 'L') ? 'selected' : '' }}>Laki-laki (L)</option>
                                                                <option value="P" {{ ($pengajuan->santri && $pengajuan->santri->jenis_kelamin == 'P') ? 'selected' : '' }}>Perempuan (P)</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Tempat Lahir</label>
                                                            <input type="text" name="tempat_lahir" id="ro_tempat_lahir" class="form-control req-tab1" placeholder="Tempat Lahir" value="{{ $pengajuan->santri ? $pengajuan->santri->tempat_lahir : '' }}">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Tanggal Lahir</label>
                                                            <input type="date" name="tanggal_lahir" id="ro_tanggal_lahir" class="form-control req-tab1" value="{{ $pengajuan->santri ? $pengajuan->santri->tanggal_lahir : '' }}">
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <label class="form-label">Anak Ke</label>
                                                            <input type="number" name="anak_ke" id="ro_anak_ke" class="form-control" value="{{ $pengajuan->santri ? $pengajuan->santri->anak_ke : '' }}" placeholder="Anak Ke">
                                                        </div>
                                                        <div class="col-md-3 mb-3">
                                                            <label class="form-label">Jml Saudara</label>
                                                            <input type="number" name="jumlah_saudara" id="ro_jumlah_saudara" class="form-control" value="{{ $pengajuan->santri ? $pengajuan->santri->jumlah_saudara : '' }}" placeholder="Jml Saudara">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Kamar</label>
                                                            <input type="text" name="kamar" id="ro_kamar" class="form-control req-tab1" placeholder="Kamar" value="{{ $pengajuan->santri ? $pengajuan->santri->kamar : '' }}">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Nama Wali</label>
                                                            <input type="text" name="nama_wali" id="ro_wali" class="form-control req-tab1" placeholder="Nama Wali" value="{{ $pengajuan->santri ? $pengajuan->santri->nama_wali : '' }}">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Pekerjaan Wali</label>
                                                            <input type="text" name="pekerjaan_wali" id="ro_pekerjaan" class="form-control req-tab1" placeholder="Pekerjaan Wali" value="{{ $pengajuan->santri ? $pengajuan->santri->pekerjaan_wali : '' }}">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">No HP Wali</label>
                                                            <input type="text" name="no_hp_wali" id="ro_hp" class="form-control req-tab1" placeholder="No HP Wali" value="{{ $pengajuan->santri ? $pengajuan->santri->no_hp_wali : '' }}">
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
                                                            <label class="form-label">Alamat Lengkap</label>
                                                            <textarea name="alamat_lengkap" id="ro_alamat" class="form-control req-tab1" rows="2" placeholder="Alamat Lengkap">{{ $pengajuan->santri ? $pengajuan->santri->alamat_lengkap : '' }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- ===== TAB 2: INFORMASI PENUNJANG & DOKUMEN ===== --}}
                                                <div class="tab-pane" id="tab-info-penunjang" role="tabpanel">
                                                    <h5 class="font-size-14 mt-3 mb-3">Informasi Penunjang</h5>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Prestasi Kamar</label>
                                                            <textarea name="prestasi_kamar" class="form-control" rows="3">{{ old('prestasi_kamar', $pengajuan->prestasi_kamar) }}</textarea>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Isian BHS</label>
                                                            <textarea name="isian_bhs" class="form-control" rows="3">{{ old('isian_bhs', $pengajuan->isian_bhs) }}</textarea>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Kondisi Ekonomi</label>
                                                            <textarea name="kondisi_ekonomi" class="form-control" rows="3">{{ old('kondisi_ekonomi', $pengajuan->kondisi_ekonomi) }}</textarea>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Prestasi Qiro'ati</label>
                                                            <textarea name="prestasi_qiroati" class="form-control" rows="3">{{ old('prestasi_qiroati', $pengajuan->prestasi_qiroati) }}</textarea>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Prestasi Madrasah</label>
                                                            <textarea name="prestasi_madrasah" class="form-control" rows="3">{{ old('prestasi_madrasah', $pengajuan->prestasi_madrasah) }}</textarea>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Prestasi Sekolah / PT</label>
                                                            <textarea name="prestasi_sekolah" class="form-control" rows="3">{{ old('prestasi_sekolah', $pengajuan->prestasi_sekolah) }}</textarea>
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
                                                                'Raport Qiroati Amsilati' => false,
                                                                'SK Aktif Madrasah, Sekolah, Qiroati' => true,
                                                                'Raport Madrasah' => false,
                                                                'Raport Sekolah PT' => false,
                                                                'Fhoto Rumah' => true,
                                                                'Fhoto Bukti Rekening PLN' => true,
                                                            ];
                                                        @endphp

                                                        @foreach($jenisDokumens as $jenis => $isRequired)
                                                            @php 
                                                                $fieldName = Str::slug($jenis, '_'); 
                                                                $existingDoc = $pengajuan->dokumen->where('jenis_dokumen', $jenis)->first();
                                                            @endphp
                                                            <div class="col-md-6 mb-3">
                                                                <div class="d-flex justify-content-between align-items-end mb-1">
                                                                    <label class="form-label mb-0">{{ $jenis }} {!! !$isRequired ? '<small class="text-muted fw-normal">- Opsional</small>' : '<span class="text-danger">*</span>' !!}</label>
                                                                    <a href="javascript:void(0)" class="text-primary {{ $existingDoc ? 'btn-preview-existing' : 'd-none btn-preview' }}" 
                                                                       {!! $existingDoc ? 'data-url="' . route('dokumen.lihat', $existingDoc->id, false) . '"' : '' !!} 
                                                                       style="font-size: 13px;">
                                                                        <i class="ri-eye-line align-middle me-1"></i> Preview
                                                                    </a>
                                                                </div>
                                                                <input type="file" name="dokumen[{{ $jenis }}]" class="form-control" accept=".pdf">
                                                                @if($existingDoc)
                                                                    <small class="text-success"><i class="ri-check-line"></i> Dokumen sudah terunggah</small>
                                                                @endif
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
                                                        
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label fw-semibold">Qiro'ati / Amsilati</label>
                                                            <select name="lembaga_qiroati_id" class="form-select select2">
                                                                <option value="">-- Pilih Lembaga --</option>
                                                                @foreach($lembagas as $lembaga)
                                                                    <option value="{{ $lembaga->id }}" {{ old('lembaga_qiroati_id', $pengajuan->lembaga_qiroati_id) == $lembaga->id ? 'selected' : '' }}>
                                                                        {{ $lembaga->nama_lembaga }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label fw-semibold">Kelas/Smt Qiro'ati</label>
                                                            <input type="text" name="kelas_qiroati" class="form-control" value="{{ old('kelas_qiroati', $pengajuan->kelas_qiroati) }}" placeholder="Contoh: Kelas 3 / Smt 1">
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label fw-semibold">Lembaga 1 (Madrasah) <span class="text-danger">*</span></label>
                                                            <select name="lembaga_1_id" class="form-select select2 req-tab3">
                                                                <option value="">-- Pilih Madrasah --</option>
                                                                @foreach($lembagas as $lembaga)
                                                                    <option value="{{ $lembaga->id }}" {{ old('lembaga_1_id', $pengajuan->lembaga_1_id) == $lembaga->id ? 'selected' : '' }}>
                                                                        {{ $lembaga->nama_lembaga }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label fw-semibold">Kelas Pendidikan 1 <span class="text-danger">*</span></label>
                                                            <input type="text" name="kelas_pendidikan_1" class="form-control req-tab3" placeholder="Contoh: X" value="{{ old('kelas_pendidikan_1', $pengajuan->kelas_pendidikan_1) }}">
                                                        </div>

                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Lembaga 2 (Sekolah/PT) <small class="text-muted">- Opsional</small></label>
                                                            <select name="lembaga_2_id" class="form-select select2">
                                                                <option value="">-- Pilih Sekolah/PT --</option>
                                                                @foreach($lembagas as $lembaga)
                                                                    <option value="{{ $lembaga->id }}" {{ old('lembaga_2_id', $pengajuan->lembaga_2_id) == $lembaga->id ? 'selected' : '' }}>
                                                                        {{ $lembaga->nama_lembaga }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Kelas Pendidikan 2 <small class="text-muted">- Opsional</small></label>
                                                            <input type="text" name="kelas_pendidikan_2" class="form-control" placeholder="Contoh: XI" value="{{ old('kelas_pendidikan_2', $pengajuan->kelas_pendidikan_2) }}">
                                                        </div>

                                                        <div class="col-12"><hr class="my-2"></div>

                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label fw-semibold">UTAP (Rp) <span class="text-danger">*</span></label>
                                                            <input type="text" name="jumlah_utap" class="form-control req-tab3 format-rupiah" placeholder="0" value="{{ old('jumlah_utap', number_format($pengajuan->jumlah_utap, 0, ',', '.')) }}">
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label fw-semibold">SPP Smt. Gasal (Rp) <span class="text-danger">*</span></label>
                                                            <input type="text" name="spp_gasal" class="form-control req-tab3 format-rupiah" placeholder="0" value="{{ old('spp_gasal', number_format($pengajuan->spp_gasal, 0, ',', '.')) }}">
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label fw-semibold">SPP Smt. Genap (Rp) <span class="text-danger">*</span></label>
                                                            <input type="text" name="spp_genap" class="form-control req-tab3 format-rupiah" placeholder="0" value="{{ old('spp_genap', number_format($pengajuan->spp_genap, 0, ',', '.')) }}">
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label fw-semibold">Living Kost (Rp) <span class="text-danger">*</span></label>
                                                            <input type="text" name="living_cost" class="form-control req-tab3 format-rupiah" placeholder="0" value="{{ old('living_cost', number_format($pengajuan->living_cost, 0, ',', '.')) }}">
                                                        </div>
                                                        <div class="col-md-4 mb-3">
                                                            <label class="form-label fw-semibold">Biaya Lain (Rp) <span class="text-danger">*</span></label>
                                                            <input type="text" name="biaya_lain" class="form-control req-tab3 format-rupiah" placeholder="0" value="{{ old('biaya_lain', number_format($pengajuan->biaya_lain, 0, ',', '.')) }}">
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
                                                <button type="submit" class="btn btn-primary waves-effect waves-light d-none" id="btn-simpan">
                                                    <i class="ri-save-line me-1"></i> Simpan Pengajuan
                                                </button>
                                            </div>

                                        </form>

                                        <!-- Preview Modal -->
                                        <div class="modal fade" id="previewModal" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-xl modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Preview Dokumen</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body p-0">
                                                        <iframe src="" width="100%" height="75vh" style="border: none; min-height: 600px;"></iframe>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- End Page-content -->

                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <script>document.write(new Date().getFullYear())</script> © ZIZTREN.
                            </div>
                        </div>
                    </div>
                </footer>
                
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- JAVASCRIPT -->
        <script src="{{ asset("assets/libs/jquery/jquery.min.js") }}"></script>
        <script src="{{ asset("assets/libs/bootstrap/js/bootstrap.bundle.min.js") }}"></script>
        <script src="{{ asset("assets/libs/metismenu/metisMenu.min.js") }}"></script>
        <script src="{{ asset("assets/libs/simplebar/simplebar.min.js") }}"></script>
        <script src="{{ asset("assets/libs/node-waves/waves.min.js") }}"></script>
        
        <!-- Select2 js -->
        <script src="{{ asset("assets/libs/select2/js/select2.min.js") }}"></script>

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

                // Tidak ada inisialisasi Select2 untuk #nis karena diubah menjadi readonly input text
                // Live Validation Function
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
                    
                    
                    // Check Tab 2
                    var tab2_complete = true;
                    $('.req-tab2').each(function() {
                        if ($(this).val() === '' || $(this).val() === null) {
                            tab2_complete = false;
                        }
                    });
                    
                    if (tab2_complete) {
                        $('#wizard-step-2').addClass('completed');
                        $('#wizard-step-2 .step-circle').html('<i class="ri-check-line"></i>');
                    } else {
                        $('#wizard-step-2').removeClass('completed');
                        $('#wizard-step-2 .step-circle').html('<i class="ri-file-text-line"></i>');
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
                    var previewBtn = $(this).siblings('.d-flex').find('.btn-preview');
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
                });

                // Preview for existing documents
                $('.btn-preview-existing').on('click', function() {
                    var fileUrl = $(this).data('url');
                    $('#previewModal iframe').attr('src', fileUrl);
                    $('#previewModal').modal('show');
                });
                
                // Initial check on load
                setTimeout(checkTabs, 500);

                // Tab Navigation Logic
                var currentTab = 0;
                var tabs = ['#tab-data-pemohon', '#tab-info-penunjang', '#tab-jumlah-bantuan'];
                
                function updateButtons() {
                    $('#btn-prev').toggleClass('d-none', currentTab === 0);
                    $('#btn-next').toggleClass('d-none', currentTab === tabs.length - 1);
                    $('#btn-simpan').toggleClass('d-none', currentTab !== tabs.length - 1);
                }

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

            });
        </script>

        <!-- App js -->
        <script src="{{ asset("assets/js/app.js") }}"></script>
    </body>

</html>