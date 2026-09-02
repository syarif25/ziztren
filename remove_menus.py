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

    # Pattern to remove everything between vertical-menu-btn and user-dropdown
    pattern1 = r'(<button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">\s*<i class="ri-menu-2-line align-middle"></i>\s*</button>).*?(<div class="dropdown d-inline-block user-dropdown">)'
    content = re.sub(pattern1, r'\1\n\n                        \2', content, flags=re.DOTALL)

    # Pattern to remove the settings gear toggle
    pattern2 = r'<div class="dropdown d-inline-block">\s*<button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">\s*<i class="ri-settings-2-line"></i>\s*</button>\s*</div>'
    content = re.sub(pattern2, '', content, flags=re.DOTALL)

    with open(file, 'w') as f:
        f.write(content)

print("Done")
