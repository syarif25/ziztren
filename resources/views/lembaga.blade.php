@extends('layouts.app')

@section('title', 'Data Lembaga')
@section('page-title', 'Data Lembaga')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
    <li class="breadcrumb-item active">Lembaga</li>
@endsection

@section('content')
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
@endsection

@section('modals')
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            var table = $('#table-lembaga').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('lembaga.data') }}",
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

            $('#btn-tambah').click(function() {
                $('#form-lembaga')[0].reset();
                $('#lembaga_id').val('');
                $('#modal-title').text('Tambah Lembaga');
                $('.is-invalid').removeClass('is-invalid');
                $('#modal-lembaga').modal('show');
            });

            $('body').on('click', '.edit-btn', function () {
                var id = $(this).data('id');
                $.get("{{ route('lembaga.index') }}" + '/' + id + '/edit', function (data) {
                    $('#modal-title').text('Edit Lembaga');
                    $('.is-invalid').removeClass('is-invalid');
                    $('#lembaga_id').val(data.id);
                    $('#kode_lembaga').val(data.kode_lembaga);
                    $('#nama_lembaga').val(data.nama_lembaga);
                    $('#bidang').val(data.bidang);
                    $('#jenis_lembaga').val(data.jenis_lembaga);
                    $('#modal-lembaga').modal('show');
                });
            });

            $('#form-lembaga').submit(function(e) {
                e.preventDefault();
                var id = $('#lembaga_id').val();
                var actionType = id ? "PUT" : "POST";
                var actionUrl = id ? "{{ route('lembaga.index') }}/" + id : "{{ route('lembaga.store') }}";
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
                        if (xhr.status === 422) {
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
                            url: "{{ route('lembaga.index') }}/" + id,
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
@endpush
