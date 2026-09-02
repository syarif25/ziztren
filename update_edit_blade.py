import re

def update_edit_blade():
    filepath = '/Users/DataSaya/Docker Engine/zistren/ziztren_app/resources/views/beasiswa/edit.blade.php'
    with open(filepath, 'r') as f:
        content = f.read()

    # HTML addition
    target_html = r'(<div class="col-md-12 mb-3">\s*<label class="form-label">Alamat Lengkap</label>)'
    replacement_html = r'''<div class="col-md-6 mb-3">
                                                            <label class="form-label">Provinsi</label>
                                                            <select name="provinsi_id" id="ro_provinsi" class="form-select select2">
                                                                <option value="">-- Pilih Provinsi --</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Kabupaten/Kota</label>
                                                            <select name="kabupaten_id" id="ro_kabupaten" class="form-select select2" disabled>
                                                                <option value="">-- Pilih Kabupaten/Kota --</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Kecamatan</label>
                                                            <select name="kecamatan_id" id="ro_kecamatan" class="form-select select2" disabled>
                                                                <option value="">-- Pilih Kecamatan --</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Desa/Kelurahan</label>
                                                            <select name="desa_id" id="ro_desa" class="form-select select2" disabled>
                                                                <option value="">-- Pilih Desa/Kelurahan --</option>
                                                            </select>
                                                        </div>
                                                        \1'''
    content = re.sub(target_html, replacement_html, content)

    # JS logic at end
    target_end_js = r"(</script>\s*@endsection)"
    replacement_end_js = r'''
<script>
$(document).ready(function() {
    let initialProv = "{{ $pengajuan->santri ? $pengajuan->santri->provinsi_id : '' }}";
    let initialKab = "{{ $pengajuan->santri ? $pengajuan->santri->kabupaten_id : '' }}";
    let initialKec = "{{ $pengajuan->santri ? $pengajuan->santri->kecamatan_id : '' }}";
    let initialDesa = "{{ $pengajuan->santri ? $pengajuan->santri->desa_id : '' }}";

    // Load provinces
    $.get('/api/regions/provinsi', function(data) {
        let options = '<option value="">-- Pilih Provinsi --</option>';
        data.forEach(function(item) {
            let selected = (initialProv == item.id) ? 'selected' : '';
            options += `<option value="${item.id}" ${selected}>${item.name}</option>`;
        });
        $('#ro_provinsi').html(options);
        if(initialProv) {
            $('#ro_provinsi').trigger('change');
        }
    });

    $('#ro_provinsi').change(function() {
        let provId = $(this).val();
        $('#ro_kabupaten').html('<option value="">-- Pilih Kabupaten/Kota --</option>').prop('disabled', true);
        $('#ro_kecamatan').html('<option value="">-- Pilih Kecamatan --</option>').prop('disabled', true);
        $('#ro_desa').html('<option value="">-- Pilih Desa/Kelurahan --</option>').prop('disabled', true);
        
        if (provId) {
            $.get('/api/regions/kota/' + provId, function(data) {
                let options = '<option value="">-- Pilih Kabupaten/Kota --</option>';
                data.forEach(function(item) {
                    let selected = (initialKab == item.id) ? 'selected' : '';
                    options += `<option value="${item.id}" ${selected}>${item.name}</option>`;
                });
                $('#ro_kabupaten').html(options).prop('disabled', false);
                
                if (initialKab && provId == initialProv) {
                    $('#ro_kabupaten').trigger('change');
                } else {
                    initialKab = ''; // Reset initial after first load if changed manually
                }
            });
        }
    });

    $('#ro_kabupaten').change(function() {
        let kotaId = $(this).val();
        $('#ro_kecamatan').html('<option value="">-- Pilih Kecamatan --</option>').prop('disabled', true);
        $('#ro_desa').html('<option value="">-- Pilih Desa/Kelurahan --</option>').prop('disabled', true);
        
        if (kotaId) {
            $.get('/api/regions/kecamatan/' + kotaId, function(data) {
                let options = '<option value="">-- Pilih Kecamatan --</option>';
                data.forEach(function(item) {
                    let selected = (initialKec == item.id) ? 'selected' : '';
                    options += `<option value="${item.id}" ${selected}>${item.name}</option>`;
                });
                $('#ro_kecamatan').html(options).prop('disabled', false);

                if (initialKec && kotaId == "{{ $pengajuan->santri ? $pengajuan->santri->kabupaten_id : '' }}") {
                    $('#ro_kecamatan').trigger('change');
                } else {
                    initialKec = '';
                }
            });
        }
    });

    $('#ro_kecamatan').change(function() {
        let kecId = $(this).val();
        $('#ro_desa').html('<option value="">-- Pilih Desa/Kelurahan --</option>').prop('disabled', true);
        
        if (kecId) {
            $.get('/api/regions/desa/' + kecId, function(data) {
                let options = '<option value="">-- Pilih Desa/Kelurahan --</option>';
                data.forEach(function(item) {
                    let selected = (initialDesa == item.id) ? 'selected' : '';
                    options += `<option value="${item.id}" ${selected}>${item.name}</option>`;
                });
                $('#ro_desa').html(options).prop('disabled', false);
                initialDesa = '';
            });
        }
    });
});
</script>
\1'''
    content = re.sub(target_end_js, replacement_end_js, content)

    with open(filepath, 'w') as f:
        f.write(content)

update_edit_blade()
print("Updated edit.blade.php")
