@extends('layouts.app')

@section('title', 'Master Periode')
@section('page-title', 'Data Master Periode')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
    <li class="breadcrumb-item active">Master Periode</li>
@endsection

@section('content')
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
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
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
@endsection

@push('scripts')
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
                $(this).val() == 'aktif' ? $('#alert-aktif').show() : $('#alert-aktif').hide();
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
                $.get("{{ route('master-periode.index') }}" + '/' + id + '/edit', function (data) {
                    $('#modal-title').text('Edit Periode');
                    $('#id').val(data.id);
                    $('#tahun_masehi').val(data.tahun_masehi);
                    $('#gelombang').val(data.gelombang);
                    $('#tgl_buka').val(data.tgl_buka);
                    $('#tgl_tutup').val(data.tgl_tutup);
                    $('#kuota').val(data.kuota);
                    $('#status_periode').val(data.status);
                    data.status == 'aktif' ? $('#alert-aktif').show() : $('#alert-aktif').hide();
                    $('#modal-form').modal('show');
                });
            });

            $('#form-periode').submit(function(e) {
                e.preventDefault();
                var id   = $('#id').val();
                var url  = id ? "{{ route('master-periode.index') }}/" + id : "{{ route('master-periode.store') }}";
                var type = id ? "PUT" : "POST";

                $('#btn-save').html('Menyimpan...').attr('disabled', true);
                $.ajax({
                    url: url, type: type, data: $(this).serialize(),
                    success: function(response) {
                        $('#btn-save').html('Simpan').attr('disabled', false);
                        if (response.success) {
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
@endpush
