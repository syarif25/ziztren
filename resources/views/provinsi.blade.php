@extends('layouts.app')

@section('title', 'Data Provinsi')
@section('page-title', 'Data Provinsi')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="javascript: void(0);">Master</a></li>
    <li class="breadcrumb-item active">Provinsi</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <h4 class="card-title m-0">Daftar Wilayah Administratif</h4>
                        <p class="card-title-desc">Data seluruh provinsi di Indonesia. Data ini bersifat statis (Read-Only).</p>
                    </div>
                    <div class="table-responsive">
                        <table id="table-kota" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr><th width="5%">No</th><th>Nama Provinsi</th></tr>
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
            $('#table-kota').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('provinsi.data') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'name', name: 'name'}
                ]
            });
        });
    </script>
@endpush
