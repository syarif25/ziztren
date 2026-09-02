import os

def update_file(filename, title, breadcrumb, desc, th_list, route, columns, active_route):
    filepath = os.path.join('resources/views', filename)
    with open(filepath, 'r') as f:
        content = f.read()
    
    content = content.replace('Data Kota/Kabupaten | ZIZTREN', f'{title} | ZIZTREN')
    content = content.replace('Data Kota/Kabupaten', title)
    content = content.replace('<li class="breadcrumb-item active">Kota</li>', f'<li class="breadcrumb-item active">{breadcrumb}</li>')
    content = content.replace('Data seluruh kota dan kabupaten di Indonesia.', desc)
    
    # Replace table headers
    th_html = ''.join(f'<th>{th}</th>' for th in th_list)
    table_head = f'<tr><th width="5%">No</th>{th_html}</tr>'
    
    import re
    content = re.sub(r'<tr>\s*<th width="5%">No</th>.*?</tr>', table_head, content, flags=re.DOTALL)
    
    # Replace javascript
    content = content.replace("ajax: \"{{ route('kota.data') }}\",", f"ajax: \"{{ route('{route}.data') }}\",")
    
    col_str = ',\n                        '.join(columns)
    col_html = f"columns: [\n                        {{data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false}},\n                        {col_str}\n                    ]"
    
    content = re.sub(r'columns: \[.*?\]', col_html, content, flags=re.DOTALL)
    
    # Replace sidebar active class
    content = content.replace('<li class="mm-active"><a href="{{ route(\'kota.index\') }}" class="active">Kota</a></li>', '<li><a href="{{ route(\'kota.index\') }}">Kota</a></li>')
    content = content.replace(f'<li><a href="{{{{ route(\'{active_route}.index\') }}}}">{breadcrumb}</a></li>', f'<li class="mm-active"><a href="{{{{ route(\'{active_route}.index\') }}}}" class="active">{breadcrumb}</a></li>')
    
    with open(filepath, 'w') as f:
        f.write(content)
    print(f"Updated {filename}")

update_file('provinsi.blade.php', 'Data Provinsi', 'Provinsi', 'Data seluruh provinsi di Indonesia.', 
            ['Nama Provinsi'], 'provinsi', 
            ["{data: 'name', name: 'name'}"], 'provinsi')

update_file('kecamatan.blade.php', 'Data Kecamatan', 'Kecamatan', 'Data seluruh kecamatan di Indonesia.', 
            ['Nama Provinsi', 'Nama Kota/Kabupaten', 'Nama Kecamatan'], 'kecamatan', 
            ["{data: 'provinsi', name: 'city.province.name'}", "{data: 'kota', name: 'city.name'}", "{data: 'name', name: 'name'}"], 'kecamatan')

update_file('desa.blade.php', 'Data Desa/Kelurahan', 'Desa/Kelurahan', 'Data seluruh desa dan kelurahan di Indonesia.', 
            ['Nama Provinsi', 'Nama Kota/Kabupaten', 'Nama Kecamatan', 'Nama Desa/Kelurahan'], 'desa', 
            ["{data: 'provinsi', name: 'district.city.province.name'}", "{data: 'kota', name: 'district.city.name'}", "{data: 'kecamatan', name: 'district.name'}", "{data: 'name', name: 'name'}"], 'desa')

