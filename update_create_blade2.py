import re

def update_create_blade():
    filepath = '/Users/DataSaya/Docker Engine/zistren/ziztren_app/resources/views/beasiswa/create.blade.php'
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

    # JS addition
    target_js = r"(\$\('#ro_alamat'\)\.val\(data\.alamat_lengkap \|\| ''\);)"
    replacement_js = r'''\1
                                    
                                    // Region auto-fill logic
                                    if(data.provinsi_id) {
                                        $('#ro_provinsi').val(data.provinsi_id).trigger('change');
                                        setTimeout(function() {
                                            if(data.kabupaten_id) {
                                                $('#ro_kabupaten').val(data.kabupaten_id).trigger('change');
                                                setTimeout(function() {
                                                    if(data.kecamatan_id) {
                                                        $('#ro_kecamatan').val(data.kecamatan_id).trigger('change');
                                                        setTimeout(function() {
                                                            if(data.desa_id) {
                                                                $('#ro_desa').val(data.desa_id).trigger('change');
                                                            }
                                                        }, 1000);
                                                    }
                                                }, 1000);
                                            }
                                        }, 1000);
                                    }'''
    content = re.sub(target_js, replacement_js, content)

    # JS logic at end
    target_end_js = r"(</script>\s*@endsection)"
    replacement_end_js = r'''
<script>
$(document).ready(function() {
    // Load provinces
    $.get('/api/regions/provinsi', function(data) {
        let options = '<option value="">-- Pilih Provinsi --</option>';
        data.forEach(function(item) {
            options += `<option value="${item.id}">${item.name}</option>`;
        });
        $('#ro_provinsi').html(options);
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
                    options += `<option value="${item.id}">${item.name}</option>`;
                });
                $('#ro_kabupaten').html(options).prop('disabled', false);
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
                    options += `<option value="${item.id}">${item.name}</option>`;
                });
                $('#ro_kecamatan').html(options).prop('disabled', false);
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
                    options += `<option value="${item.id}">${item.name}</option>`;
                });
                $('#ro_desa').html(options).prop('disabled', false);
            });
        }
    });
});
</script>
\1'''
    content = re.sub(target_end_js, replacement_end_js, content)

    with open(filepath, 'w') as f:
        f.write(content)

update_create_blade()
print("Updated create.blade.php")
