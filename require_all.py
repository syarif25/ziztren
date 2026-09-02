import re

filepath = 'resources/views/beasiswa/create.blade.php'

with open(filepath, 'r') as f:
    content = f.read()

# Tab 1: Make all text/select/date/textarea required
t1_inputs = [
    'name="jenis_kelamin"',
    'name="kamar"',
    'name="pekerjaan_wali"',
    'name="no_hp_wali"'
]
for t1 in t1_inputs:
    content = content.replace(f'{t1} id', f'{t1} class="req-tab1" id')
    content = content.replace(f'{t1} class="form-control"', f'{t1} class="form-control req-tab1"')
    content = content.replace(f'{t1} class="form-select"', f'{t1} class="form-select req-tab1"')

# Tab 2: Make all text inputs required (prestasi_kamar, isian_bhs, kondisi_ekonomi, prestasi_qiroati, prestasi_madrasah, prestasi_sekolah)
t2_inputs = [
    'name="prestasi_kamar"',
    'name="isian_bhs"',
    'name="kondisi_ekonomi"',
    'name="prestasi_qiroati"',
    'name="prestasi_madrasah"',
    'name="prestasi_sekolah"'
]
for t2 in t2_inputs:
    content = content.replace(f'{t2} class="form-control"', f'{t2} class="form-control req-tab2"')

# Tab 3: Make all inputs required (lembaga_2_id, kelas_pendidikan_2)
t3_inputs = [
    'name="lembaga_2_id"',
    'name="kelas_pendidikan_2"'
]
for t3 in t3_inputs:
    content = content.replace(f'{t3} class="form-control"', f'{t3} class="form-control req-tab3"')
    content = content.replace(f'{t3} class="form-select select2"', f'{t3} class="form-select select2 req-tab3"')


# Update JS logic to include tab2
js_target = r"// Update Tab 2 \(always complete for now\)\s*\$\('#wizard-step-2 \.step-circle'\)\.html\('<i class=\"ri-check-line\"></i>'\);"

js_replace = '''
                    // Check Tab 2
                    var tab2_complete = true;
                    $('.req-tab2').each(function() {
                        if ($(this).val() === '' || $(this).val() === null) {
                            tab2_complete = false;
                        }
                    });
                    
                    if (tab2_complete) {
                        $('#ind-tab2').removeClass('bg-danger').addClass('bg-success').text('✅');
                        $('#wizard-step-2').addClass('completed');
                        $('#wizard-step-2 .step-circle').html('<i class="ri-check-line"></i>');
                    } else {
                        $('#ind-tab2').removeClass('bg-success').addClass('bg-danger').text('❌');
                        $('#wizard-step-2').removeClass('completed');
                        $('#wizard-step-2 .step-circle').html('<i class="ri-file-text-line"></i>');
                    }
'''

content = re.sub(js_target, js_replace, content)

# Remove the default completed from wizard-step-2 HTML
content = content.replace('<div class="wizard-step completed" id="wizard-step-2">', '<div class="wizard-step" id="wizard-step-2">')

with open(filepath, 'w') as f:
    f.write(content)

print("Added required validation to all inputs")
