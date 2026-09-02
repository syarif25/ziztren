import os
import re

files = [
    "resources/views/dashboard.blade.php",
    "resources/views/kota.blade.php",
    "resources/views/pengguna.blade.php",
    "resources/views/lembaga.blade.php",
    "resources/views/santri.blade.php",
    "resources/views/beasiswa.blade.php"
]

for file in files:
    with open(file, 'r') as f:
        content = f.read()

    # Pattern to remove the user dropdown
    pattern = r'<div class="dropdown d-inline-block user-dropdown">.*?</div>\s*</div>'
    content = re.sub(pattern, '', content, flags=re.DOTALL)

    with open(file, 'w') as f:
        f.write(content)

print("Done")
