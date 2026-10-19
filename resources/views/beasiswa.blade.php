@extends('layouts.app')

@section('title', 'Pengajuan Beasiswa')
@section('page-title', 'Data Beasiswa')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="javascript: void(0);">Beasiswa</a></li>
    <li class="breadcrumb-item active">Pengajuan Beasiswa</li>
@endsection

@section('content')
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
                                    <th>Tgl Pengajuan</th>
                                    <th>Nama Santri</th>
                                    <th>Informasi Pendidikan</th>
                                    <th>Nominal Bantuan</th>
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
                                <tr><th>No</th><th>Jenis Dokumen</th><th>Ukuran</th><th>Aksi</th></tr>
                            </thead>
                            <tbody id="listDokumen"></tbody>
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
                                <button type="submit" class="btn btn-primary w-100" id="btnUpdateStatus">Update Status</button>
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
                                <tr><th>Waktu Update</th><th>Status</th><th>Tanggal Bantuan</th><th>Catatan</th></tr>
                            </thead>
                            <tbody id="listHistoriStatus"></tbody>
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
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            @if(session('success'))
                Swal.fire({ icon: 'success', title: 'Berhasil', text: '{{ session('success') }}', timer: 3000, showConfirmButton: false });
            @endif
            @if(session('error'))
                Swal.fire({ icon: 'error', title: 'Gagal', text: '{{ session('error') }}', timer: 3000, showConfirmButton: false });
            @endif

            var table = $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                orderCellsTop: true,
                order: [[1, 'desc']],
                ajax: "{{ route('beasiswa.data') }}",
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                    {data: 'created_at', name: 'created_at', render: function(data, type, row) {
                        return '<div>' + (row.tanggal_bantuan || data) + '</div><div class="text-muted small">' + row.periode + '</div>';
                    }},
                    {data: 'nama_santri', name: 'nama_santri', render: function(data, type, row) {
                        return '<div class="fw-bold text-primary">' + data + '</div><div class="text-muted small">NIS: ' + row.nis + '</div>';
                    }},
                    {data: 'madrasah', name: 'madrasah', render: function(data, type, row) {
                        var html = '';
                        if (data !== '-') html += '<div class="mb-1"><b>' + data + '</b> (Kls: ' + row.kelas_pendidikan_1 + ')</div>';
                        if (row.sekolah && row.sekolah !== '-') html += '<div><b>' + row.sekolah + '</b> (Kls: ' + (row.kelas_pendidikan_2 ? row.kelas_pendidikan_2 : '-') + ')</div>';
                        return html || '-';
                    }},
                    {data: 'utap', name: 'utap', render: function(data, type, row) {
                        return '<table class="table table-sm table-borderless mb-0" style="font-size: 0.8rem;"><tbody>' +
                            '<tr><td class="p-0 pb-1 text-muted" style="width: 75px;">UTAP</td><td class="p-0 pb-1 fw-medium">: ' + data + '</td></tr>' +
                            '<tr><td class="p-0 pb-1 text-muted">SPP Gas/Gen</td><td class="p-0 pb-1 fw-medium">: ' + row.spp_gasal + ' / ' + row.spp_genap + '</td></tr>' +
                            '<tr><td class="p-0 pb-1 text-muted">Living Cost</td><td class="p-0 pb-1 fw-medium">: ' + row.living_cost + '</td></tr>' +
                            '<tr><td class="p-0 text-muted">Biaya Lain</td><td class="p-0 fw-medium">: ' + row.biaya_lain + '</td></tr>' +
                            '</tbody></table>';
                    }},
                    {data: 'status_pengajuan', name: 'status_pengajuan', render: function(data) {
                        let status = data ? data.toLowerCase() : 'pending';
                        if (status === 'draft')     return '<span class="badge bg-secondary text-white px-2 py-1 font-size-12"><i class="ri-edit-2-line align-middle me-1"></i> Draft</span>';
                        if (status === 'proses')    return '<span class="badge bg-info text-white px-2 py-1 font-size-12"><i class="ri-loader-4-line align-middle me-1"></i> Proses</span>';
                        if (status === 'acc')       return '<span class="badge bg-primary text-white px-2 py-1 font-size-12"><i class="ri-check-double-line align-middle me-1"></i> ACC</span>';
                        if (status === 'pencairan') return '<span class="badge bg-success text-white px-2 py-1 font-size-12"><i class="ri-money-dollar-circle-line align-middle me-1"></i> Pencairan</span>';
                        return '<span class="badge bg-warning text-dark px-2 py-1 font-size-12">' + data + '</span>';
                    }},
                    {data: 'id', name: 'aksi', orderable: false, searchable: false, render: function(data) {
                        return `<a href="/beasiswa/${data}" class="btn btn-sm btn-primary px-3 shadow-sm rounded-pill"><i class="ri-eye-line align-middle me-1"></i> Lihat</a>`;
                    }}
                ],
                initComplete: function() {
                    var filterRow = $('<tr class="filters"></tr>');
                    this.api().columns().every(function(colIdx) {
                        var th = $('<th></th>');
                        if (colIdx !== 0 && colIdx !== 6) {
                            var column = this;
                            var timer = null;
                            var input = $('<input type="text" class="form-control form-control-sm" placeholder="Cari..." />')
                                .on('keyup', function() {
                                    clearTimeout(timer);
                                    var val = this.value;
                                    timer = setTimeout(function() {
                                        if (column.search() !== val) column.search(val).draw();
                                    }, 600);
                                });
                            th.append(input);
                        }
                        filterRow.append(th);
                    });
                    $(this.api().table().header()).append(filterRow);
                }
            });

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
                    if (data.length === 0) {
                        html = '<tr><td colspan="4" class="text-center">Belum ada dokumen.</td></tr>';
                    } else {
                        $.each(data, function(i, doc) {
                            html += `<tr><td>${i+1}</td><td>${doc.jenis_dokumen}</td><td>${(doc.file_size / 1024).toFixed(2)} KB</td>
                                <td><a href="/dokumen/${doc.id}" target="_blank" class="btn btn-sm btn-primary"><i class="ri-eye-line"></i> Lihat/Unduh</a></td></tr>`;
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
                    url: '/beasiswa/' + id + '/dokumen', type: 'POST', data: formData, contentType: false, processData: false,
                    success: function(res) {
                        if (res.success) {
                            Swal.fire('Berhasil!', res.message, 'success');
                            $('#formUploadDokumen')[0].reset();
                            $('#pengajuan_id').val(id);
                            loadDokumen(id);
                        }
                    },
                    error: function(xhr) {
                        var msg = 'Gagal mengunggah dokumen.';
                        if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                        Swal.fire('Error!', msg, 'error');
                    },
                    complete: function() { btn.prop('disabled', false).html(oriHtml); }
                });
            });

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
                    if (data.length === 0) {
                        html = '<tr><td colspan="4" class="text-center">Belum ada histori status.</td></tr>';
                    } else {
                        $.each(data, function(i, h) {
                            var dateObj = new Date(h.created_at);
                            var timeStr = dateObj.getFullYear() + "-" + ("0" + (dateObj.getMonth() + 1)).slice(-2) + "-" + ("0" + dateObj.getDate()).slice(-2) + " " +
                                ("0" + dateObj.getHours()).slice(-2) + ":" + ("0" + dateObj.getMinutes()).slice(-2);
                            let badge = 'bg-secondary';
                            let stat = h.status ? h.status.toLowerCase() : '';
                            if (stat === 'pending') badge = 'bg-warning text-dark';
                            else if (stat === 'proses') badge = 'bg-info text-dark';
                            else if (stat === 'acc') badge = 'bg-primary';
                            else if (stat === 'pencairan') badge = 'bg-success';
                            html += `<tr><td>${timeStr}</td><td><span class="badge ${badge}">${h.status}</span></td>
                                <td>${h.tanggal_bantuan ? h.tanggal_bantuan : '-'}</td><td>${h.catatan ? h.catatan : '-'}</td></tr>`;
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
                    url: '/beasiswa/' + id + '/status', type: 'POST', data: new FormData(this), processData: false, contentType: false,
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Berhasil', response.message, 'success');
                            loadHistoriStatus(id);
                            table.ajax.reload(null, false);
                            $('#formUpdateStatus')[0].reset();
                        } else {
                            Swal.fire('Gagal', response.message, 'error');
                        }
                    },
                    error: function(xhr) { Swal.fire('Error', 'Terjadi kesalahan sistem', 'error'); },
                    complete: function() { btn.html('Update Status').prop('disabled', false); }
                });
            });
        });
    </script>
@endpush