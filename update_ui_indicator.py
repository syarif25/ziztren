import re

filepath = 'resources/views/beasiswa/create.blade.php'

with open(filepath, 'r') as f:
    content = f.read()

# 1. Update Tab links to include indicators
t1_target = r'<span class="d-none d-sm-block"><i class="ri-user-line me-1"></i> Data Pemohon</span>'
t1_replace = r'<span class="d-none d-sm-block"><i class="ri-user-line me-1"></i> Data Pemohon <span id="ind-tab1" class="badge rounded-pill bg-danger ms-1">❌</span></span>'
content = content.replace(t1_target, t1_replace)

t2_target = r'<span class="d-none d-sm-block"><i class="ri-file-text-line me-1"></i> Informasi Penunjang & Dokumen</span>'
t2_replace = r'<span class="d-none d-sm-block"><i class="ri-file-text-line me-1"></i> Informasi Penunjang & Dokumen <span id="ind-tab2" class="badge rounded-pill bg-success ms-1">✅</span></span>'
content = content.replace(t2_target, t2_replace)

t3_target = r'<span class="d-none d-sm-block"><i class="ri-money-dollar-circle-line me-1"></i> Jumlah Bantuan</span>'
t3_replace = r'<span class="d-none d-sm-block"><i class="ri-money-dollar-circle-line me-1"></i> Jumlah Bantuan <span id="ind-tab3" class="badge rounded-pill bg-danger ms-1">❌</span></span>'
content = content.replace(t3_target, t3_replace)


# 2. Add required classes to inputs in Tab 1
t1_inputs = [
    'name="nis"',
    'name="tempat_lahir"',
    'name="tanggal_lahir"',
    'name="nama_wali"',
    'name="alamat_lengkap"'
]
for t1 in t1_inputs:
    content = content.replace(f'{t1} id', f'{t1} class="req-tab1" id')
    content = content.replace(f'{t1} class="form-control"', f'{t1} class="form-control req-tab1"')
    content = content.replace(f'{t1} class="form-select select2-ajax"', f'{t1} class="form-select select2-ajax req-tab1"')

# 3. Add required classes to inputs in Tab 3
t3_inputs = [
    'name="tahun_pelajaran"',
    'name="gelombang"',
    'name="lembaga_1_id"',
    'name="kelas_pendidikan_1"',
    'name="jumlah_utap"',
    'name="spp_gasal"',
    'name="spp_genap"',
    'name="living_cost"',
    'name="biaya_lain"'
]
for t3 in t3_inputs:
    content = content.replace(f'{t3} class="form-control"', f'{t3} class="form-control req-tab3"')
    content = content.replace(f'{t3} class="form-select select2"', f'{t3} class="form-select select2 req-tab3"')


# 4. Insert JS logic before the closing </script> tag at the end of the file
js_logic = '''
                // Live Validation Function
                function checkTabs() {
                    // Check Tab 1
                    var tab1_complete = true;
                    $('.req-tab1').each(function() {
                        if ($(this).val() === '' || $(this).val() === null) {
                            tab1_complete = false;
                        }
                    });
                    if (tab1_complete) {
                        $('#ind-tab1').removeClass('bg-danger').addClass('bg-success').text('✅');
                    } else {
                        $('#ind-tab1').removeClass('bg-success').addClass('bg-danger').text('❌');
                    }

                    // Check Tab 3
                    var tab3_complete = true;
                    $('.req-tab3').each(function() {
                        if ($(this).val() === '' || $(this).val() === null) {
                            tab3_complete = false;
                        }
                    });
                    if (tab3_complete) {
                        $('#ind-tab3').removeClass('bg-danger').addClass('bg-success').text('✅');
                    } else {
                        $('#ind-tab3').removeClass('bg-success').addClass('bg-danger').text('❌');
                    }
                }

                // Trigger check on any input change
                $('input, select, textarea').on('input change', function() {
                    checkTabs();
                });
                
                // Also trigger check after Select2 changes
                $('.select2, .select2-ajax').on('select2:select select2:unselect select2:clear', function() {
                    checkTabs();
                });
                
                // Initial check on load
                setTimeout(checkTabs, 500);
'''

# Find the closing script tag in the document ready block
content = re.sub(r'(\s*\}\);\s*</script>\s*<!-- App js -->)', js_logic + r'\1', content)

with open(filepath, 'w') as f:
    f.write(content)

print("Updated create.blade.php with real-time validation successfully.")
