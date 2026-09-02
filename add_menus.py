import os
import glob
import re

views_dir = 'resources/views'
blade_files = [f for f in os.listdir(views_dir) if f.endswith('.blade.php')]

replacement = """                                    <li><a href="{{ route('pengguna.index') }}">Pengguna</a></li>
                                    <li><a href="{{ route('lembaga.index') }}">Lembaga</a></li>
                                    <li><a href="{{ route('provinsi.index') }}">Provinsi</a></li>
                                    <li{kota_active}><a href="{{ route('kota.index') }}"{kota_class}>Kota</a></li>
                                    <li><a href="{{ route('kecamatan.index') }}">Kecamatan</a></li>
                                    <li><a href="{{ route('desa.index') }}">Desa/Kelurahan</a></li>
                                    <li><a href="{{ route('santri.index') }}">Santri</a></li>"""

for filename in blade_files:
    filepath = os.path.join(views_dir, filename)
    with open(filepath, 'r') as f:
        content = f.read()
    
    # Simple regex to find the sub-menu items
    pattern = re.compile(r'<li><a href="\{\{\s*route\(\'pengguna\.index\'\)\s*\}\}">Pengguna</a></li>.*?<li><a href="\{\{\s*route\(\'santri\.index\'\)\s*\}\}">Santri</a></li>', re.DOTALL)
    
    # Check if kota has active classes in this file
    has_kota_active = 'class="mm-active"><a href="{{ route(\'kota.index\') }}" class="active"' in content
    
    kota_active = ' class="mm-active"' if has_kota_active else ''
    kota_class = ' class="active"' if has_kota_active else ''
    
    new_replacement = replacement.format(kota_active=kota_active, kota_class=kota_class)
    
    new_content = pattern.sub(new_replacement, content)
    
    if new_content != content:
        with open(filepath, 'w') as f:
            f.write(new_content)
        print(f"Updated {filename}")
