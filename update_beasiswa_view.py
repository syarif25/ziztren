import re

filepath = 'resources/views/beasiswa.blade.php'

with open(filepath, 'r') as f:
    content = f.read()

# Replace Table Body and headers
table_target_pattern = r'<thead>\s*<tr>.*?</tr>\s*</thead>\s*<tbody>.*?</tbody>'

table_replacement = '''<thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Tahun</th>
                                                        <th>Gelombang</th>
                                                        <th>NIS</th>
                                                        <th>Nama Santri</th>
                                                        <th>Madrasah</th>
                                                        <th>Kelas Madrasah</th>
                                                        <th>Sekolah</th>
                                                        <th>Kelas Sekolah</th>
                                                        <th>UTAP</th>
                                                        <th>SPP Gasal</th>
                                                        <th>SPP Genap</th>
                                                        <th>Living Cost</th>
                                                        <th>Biaya Lain-lain</th>
                                                        <th>Tanggal Bantuan</th>
                                                        <th>Status</th>
                                                        <th>Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>'''

content = re.sub(table_target_pattern, table_replacement, content, flags=re.DOTALL)

# Add SweetAlert CSS
css_target = '<!-- DataTables -->'
css_replacement = '''<!-- Sweet Alert-->
        <link href="{{ asset("assets/libs/sweetalert2/sweetalert2.min.css") }}" rel="stylesheet" type="text/css" />
        <!-- DataTables -->'''
if 'sweetalert2.min.css' not in content:
    content = content.replace(css_target, css_replacement)

# Add Modal HTML just before </div> <!-- End Page-content -->
modal_html = '''
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
                                            <tr>
                                                <th>No</th>
                                                <th>Jenis Dokumen</th>
                                                <th>Ukuran</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="listDokumen">
                                            <!-- List dokumen ajax here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
'''
if 'dokumenModal' not in content:
    content = content.replace('</div>\n                <!-- End Page-content -->', modal_html + '\n                </div>\n                <!-- End Page-content -->')


# Replace JS
js_target_pattern = r'<script src="\{\{ asset\("assets/js/pages/datatables\.init\.js"\) \}\}\"></script>'

js_replacement = '''<!-- Sweet Alerts js -->
        <script src="{{ asset("assets/libs/sweetalert2/sweetalert2.min.js") }}"></script>

        <script>
            $(document).ready(function() {
                var table = $('#datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('beasiswa.data') }}",
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'tahun_pelajaran', name: 'tahun_pelajaran'},
                        {data: 'gelombang', name: 'gelombang'},
                        {data: 'nis', name: 'nis'},
                        {data: 'nama_santri', name: 'nama_santri'},
                        {data: 'madrasah', name: 'madrasah'},
                        {data: 'kelas_pendidikan_1', name: 'kelas_pendidikan_1'},
                        {data: 'sekolah', name: 'sekolah'},
                        {data: 'kelas_pendidikan_2', name: 'kelas_pendidikan_2'},
                        {data: 'utap', name: 'utap'},
                        {data: 'spp_gasal', name: 'spp_gasal'},
                        {data: 'spp_genap', name: 'spp_genap'},
                        {data: 'living_cost', name: 'living_cost'},
                        {data: 'biaya_lain', name: 'biaya_lain'},
                        {data: 'tanggal_bantuan', name: 'tanggal_bantuan'},
                        {data: 'status_pengajuan', name: 'status_pengajuan', render: function(data) {
                            if(data == 'pending') return '<span class="badge bg-warning">Pending</span>';
                            if(data == 'disetujui') return '<span class="badge bg-success">Disetujui</span>';
                            if(data == 'ditolak') return '<span class="badge bg-danger">Ditolak</span>';
                            return data;
                        }},
                        {data: 'id', name: 'aksi', orderable: false, searchable: false, render: function(data) {
                            return `
                                <button class="btn btn-sm btn-info me-1"><i class="ri-pencil-line"></i></button>
                                <button class="btn btn-sm btn-secondary me-1 btn-dokumen" data-id="${data}" title="Kelola Dokumen"><i class="ri-folder-2-line"></i></button>
                                <button class="btn btn-sm btn-danger"><i class="ri-delete-bin-line"></i></button>
                            `;
                        }}
                    ]
                });

                // Open Modal Dokumen
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
                        if(data.length === 0) {
                            html = '<tr><td colspan="4" class="text-center">Belum ada dokumen.</td></tr>';
                        } else {
                            $.each(data, function(i, doc) {
                                html += `<tr>
                                    <td>${i+1}</td>
                                    <td>${doc.jenis_dokumen}</td>
                                    <td>${(doc.file_size / 1024).toFixed(2)} KB</td>
                                    <td>
                                        <a href="/dokumen/${doc.id}" target="_blank" class="btn btn-sm btn-primary"><i class="ri-eye-line"></i> Lihat/Unduh</a>
                                    </td>
                                </tr>`;
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
                        url: '/beasiswa/' + id + '/dokumen',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(res) {
                            if(res.success) {
                                Swal.fire('Berhasil!', res.message, 'success');
                                $('#formUploadDokumen')[0].reset();
                                $('#pengajuan_id').val(id); // reset clears hidden input
                                loadDokumen(id);
                            }
                        },
                        error: function(xhr) {
                            var msg = 'Gagal mengunggah dokumen.';
                            if(xhr.responseJSON && xhr.responseJSON.message) {
                                msg = xhr.responseJSON.message;
                            }
                            Swal.fire('Error!', msg, 'error');
                        },
                        complete: function() {
                            btn.prop('disabled', false).html(oriHtml);
                        }
                    });
                });
            });
        </script>'''
content = re.sub(js_target_pattern, js_replacement, content)

with open(filepath, 'w') as f:
    f.write(content)

print("Updated beasiswa.blade.php")
