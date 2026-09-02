import re

filepath = 'resources/views/beasiswa/create.blade.php'

with open(filepath, 'r') as f:
    content = f.read()

# Replace Tab 1 Detail Santri section
target = r'<h5 class="font-size-14 mt-2 mb-3 text-muted">.*?</div>\s*</div>\s*</div>'

replacement = '''<h5 class="font-size-14 mt-2 mb-3 text-muted">
                                                        <i class="ri-information-line me-1"></i> Lengkapi Data Santri
                                                    </h5>
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Nama Lengkap</label>
                                                            <input type="text" id="ro_nama" class="form-control bg-light" disabled value="(Pilih santri terlebih dahulu)">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Jenis Kelamin</label>
                                                            <select name="jenis_kelamin" id="ro_jk" class="form-select">
                                                                <option value="">-- Pilih Jenis Kelamin --</option>
                                                                <option value="L">Laki-laki (L)</option>
                                                                <option value="P">Perempuan (P)</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Tempat Lahir</label>
                                                            <input type="text" name="tempat_lahir" id="ro_tempat_lahir" class="form-control" placeholder="Tempat Lahir">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Tanggal Lahir</label>
                                                            <input type="date" name="tanggal_lahir" id="ro_tanggal_lahir" class="form-control">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Kamar</label>
                                                            <input type="text" name="kamar" id="ro_kamar" class="form-control" placeholder="Kamar">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Nama Wali</label>
                                                            <input type="text" name="nama_wali" id="ro_wali" class="form-control" placeholder="Nama Wali">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Pekerjaan Wali</label>
                                                            <input type="text" name="pekerjaan_wali" id="ro_pekerjaan" class="form-control" placeholder="Pekerjaan Wali">
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">No HP Wali</label>
                                                            <input type="text" name="no_hp_wali" id="ro_hp" class="form-control" placeholder="No HP Wali">
                                                        </div>
                                                        <div class="col-md-12 mb-3">
                                                            <label class="form-label">Alamat Lengkap</label>
                                                            <textarea name="alamat_lengkap" id="ro_alamat" class="form-control" rows="2" placeholder="Alamat Lengkap"></textarea>
                                                        </div>
                                                    </div>
                                                </div>'''

content = re.sub(target, replacement, content, flags=re.DOTALL)

# Update AJAX logic to set new fields
ajax_target = r"if\s*\(data\)\s*\{\s*\$\('#ro_nama'\)\.val\(data\.nama_lengkap\);\s*\} else \{"
ajax_replacement = '''if (data) {
                                    $('#ro_nama').val(data.nama_lengkap);
                                    $('#ro_jk').val(data.jenis_kelamin || '');
                                    $('#ro_tempat_lahir').val(data.tempat_lahir || '');
                                    $('#ro_tanggal_lahir').val(data.tanggal_lahir || '');
                                    $('#ro_kamar').val(data.kamar || '');
                                    $('#ro_wali').val(data.nama_wali || '');
                                    $('#ro_pekerjaan').val(data.pekerjaan_wali || '');
                                    $('#ro_hp').val(data.no_hp_wali || '');
                                    $('#ro_alamat').val(data.alamat_lengkap || '');
                                } else {'''

content = re.sub(ajax_target, ajax_replacement, content, flags=re.DOTALL)

with open(filepath, 'w') as f:
    f.write(content)

print("Updated create.blade.php successfully.")
