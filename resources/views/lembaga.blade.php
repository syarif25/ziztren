<!doctype html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>Data Lembaga | ZIZTREN</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

        <!-- DataTables -->
        <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />  

        <!-- Bootstrap Css -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
        <!-- Sweet Alert-->
        <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    </head>

    <body data-topbar="dark">
        <div id="layout-wrapper">
            
            <header id="page-topbar">
                <div class="navbar-header">
                    <div class="d-flex">
                        <div class="navbar-brand-box">
                            <a href="{{{ route('dashboard') }}}" class="logo logo-dark">
                                <span class="logo-sm"><span class="fw-bold text-primary" style="font-size: 20px;">Z</span></span>
                                <span class="logo-lg"><span class="fw-bold text-primary" style="font-size: 22px; letter-spacing: 1px;">ZIZTREN</span></span>
                            </a>
                            <a href="{{{ route('dashboard') }}}" class="logo logo-light">
                                <span class="logo-sm"><span class="fw-bold text-white" style="font-size: 20px;">Z</span></span>
                                <span class="logo-lg"><span class="fw-bold text-white" style="font-size: 22px; letter-spacing: 1px;">ZIZTREN</span></span>
                            </a>
                        </div>
                        <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                            <i class="ri-menu-2-line align-middle"></i>
                        </button>
                    </div>
                </div>
            </header>

            <div class="vertical-menu">
                <div data-simplebar class="h-100">
                    <div class="user-profile text-center mt-3">
                        <div class="">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username ?? 'User') }}&background=random&color=fff" alt="" class="avatar-md rounded-circle">
                        </div>
                        <div class="mt-3">
                            <h4 class="font-size-16 mb-1">{{ Auth::user()->username ?? 'User' }}</h4>
                            <span class="text-muted"><i class="ri-record-circle-line align-middle font-size-14 text-success"></i> Online</span>
                        </div>
                    </div>

                    <div id="sidebar-menu">
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li class="menu-title">Menu</li>
                            <li>
                                <a href="{{{ route('dashboard') }}}" class="waves-effect">
                                    <i class="ri-dashboard-line"></i><span>Dashboard</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{{ route('beasiswa.index') }}}" class="waves-effect">
                                    <i class="ri-file-list-3-line"></i><span>Beasiswa</span>
                                </a>
                            </li>
                            <li class="mm-active">
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-database-2-line"></i><span>Master</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="true">
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
                                    <i class="ri-shut-down-line text-danger"></i><span>Logout</span>
                                </a>
                                <form id="logout-form" action="{{{ route('logout') }}}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">Data Lembaga</h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
                                            <li class="breadcrumb-item active">Lembaga</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h4 class="card-title m-0">Daftar Lembaga</h4>
                                            <button class="btn btn-primary btn-sm" id="btn-tambah"><i class="ri-add-line align-middle me-1"></i> Tambah Lembaga</button>
                                        </div>
                                        <div class="table-responsive">
                                            <table id="table-lembaga" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th width="5%">No</th>
                                                        <th>Nama Lembaga</th>
                                                        <th>Singkatan</th>
                                                        <th>Bidang</th>
                                                        <th>Jenis Lembaga</th>
                                                        <th width="10%">Aksi</th>
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
                </div>

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
        </div>

        <div class="rightbar-overlay"></div>

        <!-- Modal Form -->
        <div class="modal fade" id="modal-lembaga" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="form-lembaga">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-title">Tambah Lembaga</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" id="lembaga_id" name="id">
                            <div class="mb-3">
                                <label for="kode_lembaga" class="form-label">Kode/Singkatan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="kode_lembaga" name="kode_lembaga" required maxlength="50">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="nama_lembaga" class="form-label">Nama Lembaga <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_lembaga" name="nama_lembaga" required maxlength="150">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="bidang" class="form-label">Bidang</label>
                                <input type="text" class="form-control" id="bidang" name="bidang" maxlength="100">
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="mb-3">
                                <label for="jenis_lembaga" class="form-label">Jenis Lembaga <span class="text-danger">*</span></label>
                                <select class="form-select" id="jenis_lembaga" name="jenis_lembaga" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    <option value="pagi">Pagi</option>
                                    <option value="sore">Sore</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light waves-effect" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary waves-effect waves-light" id="btn-simpan">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- JAVASCRIPT -->
        <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>

        <!-- Required datatable js -->
        <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>
        
        <!-- Sweet Alerts js -->
        <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

        <script src="{{ asset('assets/js/app.js') }}"></script>

        <script>
            $(document).ready(function() {
                // Setup CSRF Token for all AJAX requests
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // Initialize DataTable
                var table = $('#table-lembaga').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{{ route('lembaga.data') }}}",
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'nama_lembaga', name: 'nama_lembaga'},
                        {data: 'kode_lembaga', name: 'kode_lembaga'},
                        {data: 'bidang', name: 'bidang'},
                        {
                            data: 'jenis_lembaga', 
                            name: 'jenis_lembaga',
                            render: function(data) {
                                return data.charAt(0).toUpperCase() + data.slice(1);
                            }
                        },
                        {data: 'aksi', name: 'aksi', orderable: false, searchable: false},
                    ]
                });

                // Show Modal for Create
                $('#btn-tambah').click(function() {
                    $('#form-lembaga')[0].reset();
                    $('#lembaga_id').val('');
                    $('#modal-title').text('Tambah Lembaga');
                    $('.is-invalid').removeClass('is-invalid');
                    $('#modal-lembaga').modal('show');
                });

                // Show Modal for Edit
                $('body').on('click', '.edit-btn', function () {
                    var id = $(this).data('id');
                    $.get("{{{ route('lembaga.index') }}}" +'/' + id +'/edit', function (data) {
                        $('#modal-title').text('Edit Lembaga');
                        $('.is-invalid').removeClass('is-invalid');
                        $('#lembaga_id').val(data.id);
                        $('#kode_lembaga').val(data.kode_lembaga);
                        $('#nama_lembaga').val(data.nama_lembaga);
                        $('#bidang').val(data.bidang);
                        $('#jenis_lembaga').val(data.jenis_lembaga);
                        $('#modal-lembaga').modal('show');
                    })
                });

                // Submit Form (Create/Update)
                $('#form-lembaga').submit(function(e) {
                    e.preventDefault();
                    var id = $('#lembaga_id').val();
                    var actionType = id ? "PUT" : "POST";
                    var actionUrl = id ? "{{{ route('lembaga.index') }}}/" + id : "{{{ route('lembaga.store') }}}";
                    var btn = $('#btn-simpan');
                    
                    btn.html('<i class="bx bx-loader bx-spin font-size-16 align-middle me-2"></i> Menyimpan...').prop('disabled', true);
                    $('.is-invalid').removeClass('is-invalid');

                    $.ajax({
                        data: $(this).serialize(),
                        url: actionUrl,
                        type: actionType,
                        dataType: 'json',
                        success: function (data) {
                            $('#form-lembaga')[0].reset();
                            $('#modal-lembaga').modal('hide');
                            table.draw();
                            Swal.fire('Berhasil!', data.message, 'success');
                            btn.html('Simpan').prop('disabled', false);
                        },
                        error: function (xhr) {
                            btn.html('Simpan').prop('disabled', false);
                            if(xhr.status === 422) {
                                var errors = xhr.responseJSON.errors;
                                $.each(errors, function(key, value) {
                                    $('#' + key).addClass('is-invalid');
                                    $('#' + key).siblings('.invalid-feedback').html(value[0]);
                                });
                            } else {
                                Swal.fire('Gagal!', 'Terjadi kesalahan sistem.', 'error');
                            }
                        }
                    });
                });

                // Delete Action
                $('body').on('click', '.delete-btn', function () {
                    var id = $(this).data("id");
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data lembaga ini akan dihapus secara permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                type: "DELETE",
                                url: "{{{ route('lembaga.index') }}}/" + id,
                                success: function (data) {
                                    table.draw();
                                    Swal.fire('Terhapus!', data.message, 'success');
                                },
                                error: function (xhr) {
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
