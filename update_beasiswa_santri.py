import re

filepath = 'app/Http/Controllers/BeasiswaController.php'

with open(filepath, 'r') as f:
    content = f.read()

target = r"(\s+DB::beginTransaction\(\);\s+try \{\s+)(.*?)(// Handle file uploads)"

replacement = r'''\1
            // Update Santri record if data is provided
            $santri = Santri::where('nis', $request->nis)->first();
            if ($santri) {
                $santri->update([
                    'jenis_kelamin' => $request->jenis_kelamin,
                    'tempat_lahir' => $request->tempat_lahir,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'kamar' => $request->kamar,
                    'nama_wali' => $request->nama_wali,
                    'pekerjaan_wali' => $request->pekerjaan_wali,
                    'alamat_lengkap' => $request->alamat_lengkap,
                    'no_hp_wali' => $request->no_hp_wali,
                ]);
            }

            \2\3'''

new_content = re.sub(target, replacement, content, flags=re.DOTALL)

with open(filepath, 'w') as f:
    f.write(new_content)

print("Updated BeasiswaController.php successfully.")
