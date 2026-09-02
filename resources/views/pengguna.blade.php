<!doctype html>
<html lang="en">

    <head>
        
        <meta charset="utf-8" />
        <title>Dashboard | Upcube - Admin & Dashboard Template</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
        <meta content="Themesdesign" name="author" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset("assets/images/favicon.ico") }}">

        <!-- Sweet Alert-->
        <link href="{{ asset("assets/libs/sweetalert2/sweetalert2.min.css") }}" rel="stylesheet" type="text/css" />

        <!-- jquery.vectormap css -->
        <link href="{{ asset("assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css") }}" rel="stylesheet" type="text/css" />

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
                                    <h4 class="mb-sm-0">Data Pengguna</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
                                            <li class="breadcrumb-item active">Pengguna</li>
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
                                            <h4 class="card-title mb-0">Daftar Pengguna</h4>
                                            <button type="button" class="btn btn-primary btn-sm waves-effect waves-light" id="btn-tambah">
                                                <i class="ri-add-line align-middle me-1"></i> Tambah Pengguna
                                            </button>
                                        </div>
                                        <div class="table-responsive">
                                            <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Username</th>
                                                        <th>Level</th>
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
                    
                </div>
                <!-- End Page-content -->
                
                <!-- Modal Pengguna -->
                <div class="modal fade" id="modal-pengguna" tabindex="-1" role="dialog" aria-labelledby="modalPenggunaLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalPenggunaLabel">Form Pengguna</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="form-pengguna">
                                    <input type="hidden" id="user_id" name="user_id">
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password <span id="password-help" class="text-muted" style="font-size: 12px; font-weight: normal;"></span></label>
                                        <input type="password" class="form-control" id="password" name="password">
                                    </div>
                                    <div class="mb-3">
                                        <label for="level" class="form-label">Level</label>
                                        <select class="form-select" id="level" name="level" required>
                                            <option value="">Pilih Level...</option>
                                            <option value="admin">Admin</option>
                                            <option value="operator">Operator</option>
                                        </select>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Tutup</button>
                                <button type="button" class="btn btn-primary waves-effect waves-light" id="btn-simpan">Simpan</button>
                            </div>
                        </div>
                    </div>
                </div>
               
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
        <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

        <!-- App js -->
        <script src="{{ asset("assets/js/app.js") }}"></script>

        <script>
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var table = $('#datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{{ route('pengguna.data') }}}",
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'username', name: 'username'},
                        {data: 'level', name: 'level'},
                        {data: 'status_badge', name: 'status_badge'},
                        {data: 'aksi', name: 'aksi', orderable: false, searchable: false},
                    ]
                });

                var save_method;

                $('#btn-tambah').click(function() {
                    save_method = 'add';
                    $('#form-pengguna')[0].reset();
                    $('#user_id').val('');
                    $('#modalPenggunaLabel').text('Tambah Pengguna');
                    $('#password').attr('required', true);
                    $('#password-help').text('');
                    $('#modal-pengguna').modal('show');
                });

                $('body').on('click', '.btn-edit', function() {
                    save_method = 'edit';
                    var id = $(this).data('id');
                    $.get("{{ url('pengguna') }}" + '/' + id, function(data) {
                        $('#modalPenggunaLabel').text('Edit Pengguna');
                        $('#user_id').val(data.id);
                        $('#username').val(data.username);
                        $('#level').val(data.level);
                        $('#password').attr('required', false);
                        $('#password-help').text('(Biarkan kosong jika tidak ingin mengubah password)');
                        $('#modal-pengguna').modal('show');
                    });
                });

                $('#btn-simpan').click(function(e) {
                    e.preventDefault();
                    
                    // Client-side validation fallback
                    if (!$('#form-pengguna')[0].checkValidity()) {
                        $('#form-pengguna')[0].reportValidity();
                        return;
                    }
                    
                    $(this).html('Menyimpan...').attr('disabled', true);
                    var id = $('#user_id').val();
                    var url = save_method == 'add' ? "{{{ route('pengguna.store') }}}" : "{{ url('pengguna') }}" + '/' + id;
                    var type = save_method == 'add' ? "POST" : "PUT";

                    console.log('Submit Info:', {
                        save_method: save_method,
                        id: id,
                        url: url,
                        type: type
                    });

                    if (save_method == 'edit' && !id) {
                        Swal.fire('Gagal', 'ID Pengguna tidak ditemukan!', 'error');
                        $(this).html('Simpan').attr('disabled', false);
                        return;
                    }

                    $.ajax({
                        url: url,
                        type: type,
                        data: $('#form-pengguna').serialize(),
                        success: function(response) {
                            $('#modal-pengguna').modal('hide');
                            Swal.fire('Berhasil!', response.success, 'success');
                            table.ajax.reload(null, false);
                            $('#btn-simpan').html('Simpan').attr('disabled', false);
                        },
                        error: function(xhr) {
                            $('#btn-simpan').html('Simpan').attr('disabled', false);
                            var errorMsg = 'Terjadi kesalahan!';
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                errorMsg = Object.values(xhr.responseJSON.errors).map(val => val[0]).join('<br>');
                            } else if (xhr.responseJSON && xhr.responseJSON.error) {
                                errorMsg = xhr.responseJSON.error;
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                html: errorMsg
                            });
                        }
                    });
                });

                $('body').on('click', '.btn-delete', function() {
                    var id = $(this).data('id');
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: "{{ url('pengguna') }}" + '/' + id,
                                type: "DELETE",
                                success: function(response) {
                                    Swal.fire('Terhapus!', response.success, 'success');
                                    table.ajax.reload(null, false);
                                },
                                error: function(xhr) {
                                    Swal.fire('Gagal!', 'Terjadi kesalahan saat menghapus data.', 'error');
                                }
                            });
                        }
                    });
                });
            });
        </script>
    </body>

</html>