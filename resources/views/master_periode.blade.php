<!doctype html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>Data Master Periode | ZIZTREN</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

        <!-- DataTables -->
        <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />  
        <!-- Sweet Alert-->
        <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

        <!-- Bootstrap Css -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
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
                                    <li class="mm-active"><a href="{{ route('master-periode.index') }}" class="active">Master Periode</a></li>
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
                                    <h4 class="mb-sm-0">Data Master Periode</h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
                                            <li class="breadcrumb-item active">Master Periode</li>
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
                                            <div>
                                                <h4 class="card-title m-0">Daftar Gelombang & Periode</h4>
                                                <p class="card-title-desc">Atur jadwal pendaftaran dan kuota beasiswa di sini.</p>
                                            </div>
                                            <button class="btn btn-primary" id="btn-tambah">
                                                <i class="ri-add-line align-middle me-1"></i> Tambah Periode
                                            </button>
                                        </div>
                                        <div class="table-responsive">
                                            <table id="table-periode" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th width="5%">No</th>
                                                        <th>Tahun Masehi</th>
                                                        <th>Gelombang</th>
                                                        <th>Tgl Buka</th>
                                                        <th>Tgl Tutup</th>
                                                        <th>Kuota</th>
                                                        <th>Status</th>
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
        
        <!-- Modal Form -->
        <div class="modal fade" id="modal-form" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-title">Tambah Periode</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="form-periode">
                        <div class="modal-body">
                            <input type="hidden" id="id" name="id">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tahun Masehi <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="tahun_masehi" name="tahun_masehi" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Gelombang <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="gelombang" name="gelombang" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tgl Buka <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="tgl_buka" name="tgl_buka" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tgl Tutup <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="tgl_tutup" name="tgl_tutup" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kuota <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="kuota" name="kuota" required min="1">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-control" id="status_periode" name="status" required>
                                        <option value="tutup">Tutup</option>
                                        <option value="aktif">Aktif</option>
                                    </select>
                                </div>
                            </div>
                            <div class="alert alert-warning" id="alert-aktif" style="display:none;">
                                <strong>Perhatian:</strong> Mengaktifkan periode ini akan menonaktifkan periode lain secara otomatis.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary waves-effect waves-light" id="btn-save">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="rightbar-overlay"></div>

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
                $.ajaxSetup({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                });

                var table = $('#table-periode').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('master-periode.data') }}",
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'tahun_masehi', name: 'tahun_masehi'},
                        {data: 'gelombang', name: 'gelombang'},
                        {data: 'tgl_buka', name: 'tgl_buka'},
                        {data: 'tgl_tutup', name: 'tgl_tutup'},
                        {data: 'kuota', name: 'kuota'},
                        {data: 'status', name: 'status'},
                        {data: 'action', name: 'action', orderable: false, searchable: false}
                    ]
                });

                $('#status_periode').change(function() {
                    if($(this).val() == 'aktif') {
                        $('#alert-aktif').show();
                    } else {
                        $('#alert-aktif').hide();
                    }
                });

                $('#btn-tambah').click(function() {
                    $('#form-periode')[0].reset();
                    $('#id').val('');
                    $('#modal-title').text('Tambah Periode');
                    $('#alert-aktif').hide();
                    $('#modal-form').modal('show');
                });

                $('body').on('click', '.edit-btn', function () {
                    var id = $(this).data('id');
                    $.get("{{ route('master-periode.index') }}" +'/' + id +'/edit', function (data) {
                        $('#modal-title').text('Edit Periode');
                        $('#id').val(data.id);
                        $('#tahun_masehi').val(data.tahun_masehi);
                        $('#gelombang').val(data.gelombang);
                        $('#tgl_buka').val(data.tgl_buka);
                        $('#tgl_tutup').val(data.tgl_tutup);
                        $('#kuota').val(data.kuota);
                        $('#status_periode').val(data.status);
                        
                        if(data.status == 'aktif') {
                            $('#alert-aktif').show();
                        } else {
                            $('#alert-aktif').hide();
                        }
                        
                        $('#modal-form').modal('show');
                    })
                });

                $('#form-periode').submit(function(e) {
                    e.preventDefault();
                    var id = $('#id').val();
                    var url = id ? "{{ route('master-periode.index') }}/" + id : "{{ route('master-periode.store') }}";
                    var type = id ? "PUT" : "POST";
                    
                    $('#btn-save').html('Menyimpan...').attr('disabled', true);

                    $.ajax({
                        url: url,
                        type: type,
                        data: $(this).serialize(),
                        success: function(response) {
                            $('#btn-save').html('Simpan').attr('disabled', false);
                            if(response.success) {
                                $('#modal-form').modal('hide');
                                table.draw();
                                Swal.fire("Berhasil", response.message, "success");
                            } else {
                                Swal.fire("Gagal", response.message, "error");
                            }
                        },
                        error: function(xhr) {
                            $('#btn-save').html('Simpan').attr('disabled', false);
                            Swal.fire("Error", "Terjadi kesalahan server", "error");
                        }
                    });
                });
            });
        </script>
    </body>
</html>
