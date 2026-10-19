@extends('layouts.app')

@section('title', 'Histori Pengajuan')

@push('styles')
    <!-- DataTables -->
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Sweet Alert-->
    <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('page-title', 'Manajemen Histori Pengajuan')

@section('breadcrumb')
    <li class="breadcrumb-item active">Histori Pengajuan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Pengajuan</th>
                            <th>Nama Santri</th>
                            <th>Status</th>
                            <th>Tanggal Bantuan</th>
                            <th>Catatan</th>
                            <th>Dibuat Pada</th>
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

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Histori Pengajuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm">
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="edit_status" name="status" required>
                            <option value="draft">Draft</option>
                            <option value="proses">Proses</option>
                            <option value="acc">ACC</option>
                            <option value="pencairan">Pencairan</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tanggal Bantuan</label>
                        <input type="date" class="form-control" id="edit_tanggal" name="tanggal_bantuan">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea class="form-control" id="edit_catatan" name="catatan" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary waves-effect waves-light">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
    <!-- Required datatable js -->
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Sweet Alerts js -->
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('histori-status.data') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'pengajuan_id', name: 'pengajuan_id'},
                    {data: 'santri_nama', name: 'santri_nama', searchable: false, orderable: false},
                    {data: 'status', name: 'status'},
                    {data: 'tanggal_bantuan', name: 'tanggal_bantuan'},
                    {data: 'catatan', name: 'catatan'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                order: [[6, 'desc']] // Urutkan berdasarkan created_at desc
            });

            // Setup CSRF untuk AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Modal Edit
            $('body').on('click', '.edit-histori', function () {
                var id = $(this).data('id');
                $.get("{{ url('histori-status') }}" +'/' + id +'/edit', function (data) {
                    $('#editModalLabel').text("Edit Histori Pengajuan");
                    $('#editModal').modal('show');
                    
                    $('#edit_id').val(data.id);
                    $('#edit_status').val(data.status.toLowerCase());
                    $('#edit_tanggal').val(data.tanggal_bantuan);
                    $('#edit_catatan').val(data.catatan);
                })
            });

            // Submit Edit
            $('#editForm').submit(function(e) {
                e.preventDefault();
                var id = $('#edit_id').val();
                
                $.ajax({
                    data: $(this).serialize(),
                    url: "{{ url('histori-status') }}" + '/' + id,
                    type: "PUT",
                    dataType: 'json',
                    success: function (data) {
                        $('#editModal').modal('hide');
                        table.draw();
                        Swal.fire({
                            title: 'Berhasil!',
                            text: data.message,
                            icon: 'success',
                            confirmButtonColor: '#5664d2'
                        });
                    },
                    error: function (data) {
                        console.log('Error:', data);
                        Swal.fire({
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menyimpan data.',
                            icon: 'error',
                            confirmButtonColor: '#5664d2'
                        });
                    }
                });
            });

            // Hapus Histori
            $('body').on('click', '.delete-histori', function () {
                var id = $(this).data("id");
                
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data histori ini akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#1cbb8c',
                    cancelButtonColor: '#f32f53',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "DELETE",
                            url: "{{ url('histori-status') }}"+'/'+id,
                            success: function (data) {
                                table.draw();
                                Swal.fire(
                                    'Dihapus!',
                                    data.message,
                                    'success'
                                )
                            },
                            error: function (data) {
                                console.log('Error:', data);
                                Swal.fire(
                                    'Gagal!',
                                    'Terjadi kesalahan saat menghapus data.',
                                    'error'
                                )
                            }
                        });
                    }
                })
            });
        });
    </script>
@endpush
