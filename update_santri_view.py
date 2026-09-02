import re

filepath = 'resources/views/santri.blade.php'

with open(filepath, 'r') as f:
    content = f.read()

# Replace Header to add Sync Button
header_target = '<h4 class="card-title mb-4">Daftar Santri</h4>'
header_replacement = '''<div class="d-flex justify-content-between align-items-center mb-4">
                                            <h4 class="card-title mb-0">Daftar Santri</h4>
                                            <button type="button" id="btn-sync" class="btn btn-primary btn-sm waves-effect waves-light">
                                                <i class="ri-refresh-line align-middle me-1"></i> Sinkronisasi Data
                                            </button>
                                        </div>'''
content = content.replace(header_target, header_replacement)

# Replace Table Headers and Body
table_target_pattern = r'<thead>\s*<tr>\s*<th>No</th>.*?</tr>\s*</thead>\s*<tbody>.*?</tbody>'

table_replacement = '''<thead>
                                                    <tr>
                                                        <th width="5%">No</th>
                                                        <th>NIS</th>
                                                        <th>Nama Santri</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>'''

content = re.sub(table_target_pattern, table_replacement, content, flags=re.DOTALL)

# Add SweetAlert2 CSS
css_target = '<!-- DataTables -->'
css_replacement = '''<!-- Sweet Alert-->
        <link href="{{ asset("assets/libs/sweetalert2/sweetalert2.min.css") }}" rel="stylesheet" type="text/css" />

        <!-- DataTables -->'''
content = content.replace(css_target, css_replacement)

# Replace JS datatable init and add sync logic
js_target_pattern = r'<script src="\{\{ asset\("assets/js/pages/datatables\.init\.js"\) \}\}\"></script>'

js_replacement = '''<!-- Sweet Alerts js -->
        <script src="{{ asset("assets/libs/sweetalert2/sweetalert2.min.js") }}"></script>

        <script>
            $(document).ready(function() {
                var table = $('#datatable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('santri.data') }}",
                    columns: [
                        {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                        {data: 'nis', name: 'nis'},
                        {data: 'nama_lengkap', name: 'nama_lengkap'}
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
                                data: {
                                    _token: "{{ csrf_token() }}"
                                },
                                success: function(response) {
                                    if(response.success) {
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
        </script>'''
content = re.sub(js_target_pattern, js_replacement, content)

with open(filepath, 'w') as f:
    f.write(content)

print("Updated santri.blade.php")
