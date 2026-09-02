<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Dashboard | Upcube - Admin & Dashboard Template</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesdesign" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset("assets/images/favicon.ico") }}">

        <!-- jquery.vectormap css -->
        <link href="{{ asset("assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css") }}" rel="stylesheet" type="text/css" />

        <!-- Sweet Alert-->
        <link href="{{ asset("assets/libs/sweetalert2/sweetalert2.min.css") }}" rel="stylesheet" type="text/css" />
        <!-- DataTables -->
        <link href="{{ asset("assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css") }}" rel="stylesheet" type="text/css" />

        <!-- Responsive datatable examples -->
        <link href="{{ asset("assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css") }}" rel="stylesheet" type="text/css" />  

        <!-- Bootstrap Css -->
        <link href="{{ asset("assets/css/bootstrap.min.css") }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset("assets/css/icons.min.css") }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ asset("assets/css/app.min.css") }}" id="app-style" rel="stylesheet" type="text/css" />

    </head>

    <body data-topbar="dark">
    
    <!-- <body data-layout="horizontal" data-topbar="dark"> -->

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

                            <li>
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
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">Data Beasiswa</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Beasiswa</a></li>
                                            <li class="breadcrumb-item active">Pengajuan Beasiswa</li>
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
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h4 class="card-title mb-0">Daftar Pengajuan Beasiswa</h4>
                                            <a href="{{ route('beasiswa.create') }}" class="btn btn-primary btn-sm waves-effect waves-light">
                                                <i class="ri-add-line align-middle me-1"></i> Tambah Pengajuan
                                            </a>
                                        </div>
                                        <div class="table-responsive">
                                            <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Periode Pendaftaran</th>
                                                        <th>Nama Santri</th>
                                                        <th>Madrasah</th>
                                                        <th>Sekolah</th>
                                                        <th>UTAP</th>
                                                        <th>SPP Gasal</th>
                                                        <th>SPP Genap</th>
                                                        <th>Living Cost</th>
                                                        <th>Biaya Lain-lain</th>
                                                        <th>Tanggal Bantuan</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                    </div>
                    
                
                <!-- Modal Dokumen -->
                <div class="modal fade" id="dokumenModal" tabindex="-1" role="dialog" aria-labelledby="dokumenModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="dokumenModalLabel">Kelola Dokumen Pengajuan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="formUploadDokumen" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" id="pengajuan_id" name="pengajuan_id">
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <select class="form-select" name="jenis_dokumen" id="jenis_dokumen" required>
                                                <option value="">-- Pilih Jenis Dokumen --</option>
                                                <option value="KTP">KTP</option>
                                                <option value="KK">Kartu Keluarga</option>
                                                <option value="Raport">Raport</option>
                                                <option value="Sertifikat">Sertifikat</option>
                                                <option value="Lainnya">Lainnya</option>
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <input type="file" class="form-control" name="file_dokumen" id="file_dokumen" accept="application/pdf" required>
                                            <small class="text-muted">Format: PDF. Max: 5MB.</small>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-primary w-100" id="btn-upload"><i class="ri-upload-cloud-line"></i> Upload</button>
                                        </div>
                                    </div>
                                </form>

                                <hr>
                                
                                <h6>Daftar Dokumen Terunggah</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Jenis Dokumen</th>
                                                <th>Ukuran</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="listDokumen">
                                            <!-- List dokumen ajax here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                    <!-- Modal Status & Histori -->
                    <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="statusModalLabel">Update Status & Histori</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <h6>Form Update Status</h6>
                                    <form id="formUpdateStatus">
                                        @csrf
                                        <input type="hidden" id="status_pengajuan_id" name="pengajuan_id">
                                        <div class="row mb-3">
                                            <div class="col-md-4">
                                                <label>Status Baru <span class="text-danger">*</span></label>
                                                <select class="form-select" name="status" id="input_status" required>
                                                    <option value="">-- Pilih Status --</option>
                                                    <option value="Pending">Pending</option>
                                                    <option value="Proses">Proses</option>
                                                    <option value="ACC">ACC</option>
                                                    <option value="Pencairan">Pencairan</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Tanggal Bantuan</label>
                                                <input type="date" class="form-control" name="tanggal_bantuan" id="input_tanggal">
                                            </div>
                                            <div class="col-md-4 d-flex align-items-end">
                                                <button type="submit" class="btn btn-primary w-100" id="btnUpdateStatus">
                                                    Update Status
                                                </button>
                                            </div>
                                        </div>
                                        <div class="row mb-4">
                                            <div class="col-12">
                                                <label>Catatan (Opsional)</label>
                                                <input type="text" class="form-control" name="catatan" placeholder="Keterangan tambahan saat update status...">
                                            </div>
                                        </div>
                                    </form>

                                    <hr>

                                    <h6>Riwayat Perjalanan Status</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Waktu Update</th>
                                                    <th>Status</th>
                                                    <th>Tanggal Bantuan</th>
                                                    <th>Catatan</th>
                                                </tr>
                                            </thead>
                                            <tbody id="listHistoriStatus">
                                                <!-- Data histori dimuat via AJAX -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>    <!-- End Page-content -->
               
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <script>document.write(new Date().getFullYear())</script> © Upcube.
                            </div>
                            <div class="col-sm-6">
                                <div class="text-sm-end d-none d-sm-block">
                                    Crafted with <i class="mdi mdi-heart text-danger"></i> by Themesdesign
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
                
            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- Right Sidebar -->
        <div class="right-bar">
            <div data-simplebar class="h-100">
                <div class="rightbar-title d-flex align-items-center px-3 py-4">
            
                    <h5 class="m-0 me-2">Settings</h5>

                    <a href="javascript:void(0);" class="right-bar-toggle ms-auto">
                        <i class="mdi mdi-close noti-icon"></i>
                    </a>
                </div>

                <!-- Settings -->
                <hr class="mt-0" />
                <h6 class="text-center mb-0">Choose Layouts</h6>

                <div class="p-4">
                    <div class="mb-2">
                        <img src="{{ asset("assets/images/layouts/layout-1.jpg") }}" class="img-fluid img-thumbnail" alt="layout-1">
                    </div>

                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input theme-choice" type="checkbox" id="light-mode-switch" checked>
                        <label class="form-check-label" for="light-mode-switch">Light Mode</label>
                    </div>
    
                    <div class="mb-2">
                        <img src="{{ asset("assets/images/layouts/layout-2.jpg") }}" class="img-fluid img-thumbnail" alt="layout-2">
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input theme-choice" type="checkbox" id="dark-mode-switch" data-bsStyle="assets/css/bootstrap-dark.min.css" data-appStyle="assets/css/app-dark.min.css">
                        <label class="form-check-label" for="dark-mode-switch">Dark Mode</label>
                    </div>
    
                    <div class="mb-2">
                        <img src="{{ asset("assets/images/layouts/layout-3.jpg") }}" class="img-fluid img-thumbnail" alt="layout-3">
                    </div>
                    <div class="form-check form-switch mb-5">
                        <input class="form-check-input theme-choice" type="checkbox" id="rtl-mode-switch" data-appStyle="assets/css/app-rtl.min.css">
                        <label class="form-check-label" for="rtl-mode-switch">RTL Mode</label>
                    </div>

            
                </div>

            </div> <!-- end slimscroll-menu-->
        </div>
        <!-- /Right-bar -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- JAVASCRIPT -->
        <script src="{{ asset("assets/libs/jquery/jquery.min.js") }}"></script>
        <script src="{{ asset("assets/libs/bootstrap/js/bootstrap.bundle.min.js") }}"></script>
        <script src="{{ asset("assets/libs/metismenu/metisMenu.min.js") }}"></script>
        <script src="{{ asset("assets/libs/simplebar/simplebar.min.js") }}"></script>
        <script src="{{ asset("assets/libs/node-waves/waves.min.js") }}"></script>

        
        <!-- apexcharts -->
        <script src="{{ asset("assets/libs/apexcharts/apexcharts.min.js") }}"></script>

        <!-- jquery.vectormap map -->
        <script src="{{ asset("assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js") }}"></script>
        <script src="{{ asset("assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-us-merc-en.js") }}"></script>

        <!-- Required datatable js -->
        <script src="{{ asset("assets/libs/datatables.net/js/jquery.dataTables.min.js") }}"></script>
        <script src="{{ asset("assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js") }}"></script>
        
        <!-- Responsive examples -->
        <script src="{{ asset("assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js") }}"></script>
        <script src="{{ asset("assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js") }}"></script>

        <!-- Sweet Alerts js -->
        <script src="{{ asset("assets/libs/sweetalert2/sweetalert2.min.js") }}"></script>

        <script>
            $(document).ready(function() {
                @if(session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: '{{ session('success') }}',
                        timer: 3000,
                        showConfirmButton: false
                    });
                @endif

                @if(session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: '{{ session('error') }}',
                        timer: 3000,
                        showConfirmButton: false
                    });
                @endif

                var table = $('#datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    orderCellsTop: true,
                    ajax: "{{ route('beasiswa.data') }}",
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'periode', name: 'periode'},
                        {data: 'nama_santri', name: 'nama_santri', render: function(data, type, row) {
                            return '<div class="fw-bold text-primary">' + data + '</div><div class="text-muted small">NIS: ' + row.nis + '</div>';
                        }},
                        {data: 'madrasah', name: 'madrasah', render: function(data, type, row) {
                            return data !== '-' ? ('<div class="fw-bold">' + data + '</div><div class="text-muted small">Kelas: ' + row.kelas_pendidikan_1 + '</div>') : '-';
                        }},
                        {data: 'sekolah', name: 'sekolah', render: function(data, type, row) {
                            return data !== '-' ? ('<div class="fw-bold">' + data + '</div><div class="text-muted small">Kelas: ' + (row.kelas_pendidikan_2 ? row.kelas_pendidikan_2 : '-') + '</div>') : '-';
                        }},
                        {data: 'utap', name: 'utap'},
                        {data: 'spp_gasal', name: 'spp_gasal'},
                        {data: 'spp_genap', name: 'spp_genap'},
                        {data: 'living_cost', name: 'living_cost'},
                        {data: 'biaya_lain', name: 'biaya_lain'},
                        {data: 'tanggal_bantuan', name: 'tanggal_bantuan'},
                        {data: 'status_pengajuan', name: 'status_pengajuan', render: function(data) {
                            let badge = 'bg-secondary';
                            let status = data ? data.toLowerCase() : 'pending';
                            if(status === 'pending') badge = 'bg-warning text-dark';
                            else if(status === 'proses') badge = 'bg-info text-dark';
                            else if(status === 'acc') badge = 'bg-primary';
                            else if(status === 'pencairan') badge = 'bg-success';
                            return '<span class="badge ' + badge + '">' + data + '</span>';
                        }},
                        {data: 'id', name: 'aksi', orderable: false, searchable: false, render: function(data) {
                            return `
                                <button class="btn btn-sm btn-primary me-1 btn-status" data-id="${data}" title="Update Status"><i class="ri-refresh-line"></i></button>
                                <a href="/beasiswa/${data}/edit" class="btn btn-sm btn-info me-1"><i class="ri-pencil-line"></i></a>
                                <button class="btn btn-sm btn-secondary me-1 btn-dokumen" data-id="${data}" title="Kelola Dokumen"><i class="ri-folder-2-line"></i></button>
                                <button class="btn btn-sm btn-danger"><i class="ri-delete-bin-line"></i></button>
                            `;
                        }}
                    ],
                    initComplete: function() {
                        // Buat baris filter input di bawah header
                        var filterRow = $('<tr class="filters"></tr>');
                        this.api().columns().every(function(colIdx) {
                            var th = $('<th></th>');
                            if (colIdx !== 0 && colIdx !== 12) {
                                var column = this;
                                var timer = null;
                                var input = $('<input type="text" class="form-control form-control-sm" placeholder="Cari..." />')
                                    .on('keyup', function() {
                                        clearTimeout(timer);
                                        var val = this.value;
                                        // Debounce 600ms agar tidak spam request ke server
                                        timer = setTimeout(function() {
                                            if (column.search() !== val) {
                                                column.search(val).draw();
                                            }
                                        }, 600);
                                    });
                                th.append(input);
                            }
                            filterRow.append(th);
                        });
                        $(this.api().table().header()).append(filterRow);
                    }
                });

                // Open Modal Dokumen
                $('#datatable').on('click', '.btn-dokumen', function() {
                    var id = $(this).data('id');
                    $('#pengajuan_id').val(id);
                    loadDokumen(id);
                    $('#dokumenModal').modal('show');
                });

                function loadDokumen(id) {
                    $('#listDokumen').html('<tr><td colspan="4" class="text-center"><i class="bx bx-loader bx-spin"></i> Memuat...</td></tr>');
                    $.get('/beasiswa/' + id + '/dokumen', function(data) {
                        var html = '';
                        if(data.length === 0) {
                            html = '<tr><td colspan="4" class="text-center">Belum ada dokumen.</td></tr>';
                        } else {
                            $.each(data, function(i, doc) {
                                html += `<tr>
                                    <td>${i+1}</td>
                                    <td>${doc.jenis_dokumen}</td>
                                    <td>${(doc.file_size / 1024).toFixed(2)} KB</td>
                                    <td>
                                        <a href="/dokumen/${doc.id}" target="_blank" class="btn btn-sm btn-primary"><i class="ri-eye-line"></i> Lihat/Unduh</a>
                                    </td>
                                </tr>`;
                            });
                        }
                        $('#listDokumen').html(html);
                    }).fail(function() {
                        $('#listDokumen').html('<tr><td colspan="4" class="text-center text-danger">Gagal memuat dokumen.</td></tr>');
                    });
                }

                $('#formUploadDokumen').submit(function(e) {
                    e.preventDefault();
                    var id = $('#pengajuan_id').val();
                    var formData = new FormData(this);
                    
                    var btn = $('#btn-upload');
                    var oriHtml = btn.html();
                    btn.prop('disabled', true).html('<i class="bx bx-loader bx-spin"></i> Uploading...');

                    $.ajax({
                        url: '/beasiswa/' + id + '/dokumen',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(res) {
                            if(res.success) {
                                Swal.fire('Berhasil!', res.message, 'success');
                                $('#formUploadDokumen')[0].reset();
                                $('#pengajuan_id').val(id); // reset clears hidden input
                                loadDokumen(id);
                            }
                        },
                        error: function(xhr) {
                            var msg = 'Gagal mengunggah dokumen.';
                            if(xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            }
                            Swal.fire('Error!', msg, 'error');
                        },
                        complete: function() {
                            btn.prop('disabled', false).html(oriHtml);
                        }
                    });
                });

                // Open Modal Status
                $('#datatable').on('click', '.btn-status', function() {
                    var id = $(this).data('id');
                    $('#status_pengajuan_id').val(id);
                    $('#formUpdateStatus')[0].reset();
                    loadHistoriStatus(id);
                    $('#statusModal').modal('show');
                });

                function loadHistoriStatus(id) {
                    $('#listHistoriStatus').html('<tr><td colspan="4" class="text-center"><i class="bx bx-loader bx-spin"></i> Memuat...</td></tr>');
                    $.get('/beasiswa/' + id + '/histori-status', function(data) {
                        var html = '';
                        if(data.length === 0) {
                            html = '<tr><td colspan="4" class="text-center">Belum ada histori status.</td></tr>';
                        } else {
                            $.each(data, function(i, h) {
                                // Format datetime (YYYY-MM-DD HH:mm)
                                var dateObj = new Date(h.created_at);
                                var timeStr = dateObj.getFullYear() + "-" + 
                                    ("0" + (dateObj.getMonth() + 1)).slice(-2) + "-" + 
                                    ("0" + dateObj.getDate()).slice(-2) + " " + 
                                    ("0" + dateObj.getHours()).slice(-2) + ":" + 
                                    ("0" + dateObj.getMinutes()).slice(-2);
                                
                                let badge = 'bg-secondary';
                                let stat = h.status ? h.status.toLowerCase() : '';
                                if(stat === 'pending') badge = 'bg-warning text-dark';
                                else if(stat === 'proses') badge = 'bg-info text-dark';
                                else if(stat === 'acc') badge = 'bg-primary';
                                else if(stat === 'pencairan') badge = 'bg-success';
                                
                                html += `<tr>
                                    <td>${timeStr}</td>
                                    <td><span class="badge ${badge}">${h.status}</span></td>
                                    <td>${h.tanggal_bantuan ? h.tanggal_bantuan : '-'}</td>
                                    <td>${h.catatan ? h.catatan : '-'}</td>
                                </tr>`;
                            });
                        }
                        $('#listHistoriStatus').html(html);
                    });
                }

                $('#formUpdateStatus').on('submit', function(e) {
                    e.preventDefault();
                    var id = $('#status_pengajuan_id').val();
                    var btn = $('#btnUpdateStatus');
                    btn.html('<i class="bx bx-loader bx-spin"></i> Menyimpan...').prop('disabled', true);
                    
                    $.ajax({
                        url: '/beasiswa/' + id + '/status',
                        type: 'POST',
                        data: new FormData(this),
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if(response.success) {
                                Swal.fire('Berhasil', response.message, 'success');
                                loadHistoriStatus(id);
                                table.ajax.reload(null, false);
                                $('#formUpdateStatus')[0].reset();
                            } else {
                                Swal.fire('Gagal', response.message, 'error');
                            }
                        },
                        error: function(xhr) {
                            Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
                        },
                        complete: function() {
                            btn.html('Update Status').prop('disabled', false);
                        }
                    });
                });
            });
        </script>

        <!-- App js -->
        <script src="{{ asset("assets/js/app.js") }}"></script>
    </body>

</html>