@extends('layouts.app')

@section('title', 'Data Pengguna')
@section('page-title', 'Data Pengguna')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
    <li class="breadcrumb-item active">Pengguna</li>
@endsection

@section('content')
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
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('pengguna.data') }}",
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
                if (!$('#form-pengguna')[0].checkValidity()) {
                    $('#form-pengguna')[0].reportValidity();
                    return;
                }
                $(this).html('Menyimpan...').attr('disabled', true);
                var id = $('#user_id').val();
                var url  = save_method == 'add' ? "{{ route('pengguna.store') }}" : "{{ url('pengguna') }}" + '/' + id;
                var type = save_method == 'add' ? "POST" : "PUT";

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
                        Swal.fire({ icon: 'error', title: 'Gagal', html: errorMsg });
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
@endpush