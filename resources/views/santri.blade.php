@extends('layouts.app')

@section('title', 'Data Santri')
@section('page-title', 'Data Santri')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
    <li class="breadcrumb-item active">Santri</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title mb-0">Daftar Santri</h4>
                        <button type="button" id="btn-sync" class="btn btn-primary btn-sm waves-effect waves-light">
                            <i class="ri-refresh-line align-middle me-1"></i> Sinkronisasi Data
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>NIS</th>
                                    <th>Nama Santri</th>
                                    <th>L/P</th>
                                    <th>Tempat, Tanggal Lahir</th>
                                    <th>Kamar</th>
                                    <th>Nama Wali</th>
                                    <th>No HP Wali</th>
                                    <th>Alamat Lengkap</th>
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

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('santri.data') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'nis', name: 'nis'},
                    {data: 'nama_lengkap', name: 'nama_lengkap'},
                    {data: 'jenis_kelamin', name: 'jenis_kelamin'},
                    {data: 'ttl', name: 'ttl', orderable: false, searchable: false},
                    {data: 'kamar', name: 'kamar'},
                    {data: 'nama_wali', name: 'nama_wali'},
                    {data: 'no_hp_wali', name: 'no_hp_wali'},
                    {data: 'alamat_lengkap', name: 'alamat_lengkap'}
                ]
            });

            $('#btn-sync').click(function() {
                var btn = $(this);
                Swal.fire({
                    title: 'Sinkronisasi Data?',
                    text: "Data santri akan ditarik dari E-Registrasi.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Tarik Data!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        btn.prop('disabled', true).html('<i class="bx bx-loader bx-spin font-size-16 align-middle me-2"></i> Sedang menarik data...');
                        $.ajax({
                            url: "{{ route('santri.sync') }}",
                            type: "POST",
                            data: { _token: "{{ csrf_token() }}" },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('Berhasil!', response.message, 'success');
                                    table.ajax.reload();
                                } else {
                                    Swal.fire('Gagal!', response.message, 'error');
                                }
                            },
                            error: function(xhr) {
                                Swal.fire('Error!', 'Terjadi kesalahan sistem.', 'error');
                            },
                            complete: function() {
                                btn.prop('disabled', false).html('<i class="ri-refresh-line align-middle me-1"></i> Sinkronisasi Data');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush