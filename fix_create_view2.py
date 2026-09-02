import re

# Read the template from beasiswa.blade.php
with open('resources/views/beasiswa.blade.php', 'r') as f:
    template_content = f.read()

# Read the content of create.blade.php
with open('resources/views/beasiswa/create.blade.php', 'r') as f:
    create_content = f.read()

# Remove @extends('layout') and @section('content') and @endsection from create_content
create_content = create_content.replace("@extends('layout')", "").replace("@section('content')", "").replace("@endsection", "")

# Extract main-content block from create_content
# It starts with <div class="main-content"> and ends where the scripts start.
match = re.search(r'<div class="main-content">.*?(?=<!-- Select2 CSS -->)', create_content, re.DOTALL)
if match:
    main_content_html = match.group(0)
    
    # We also need the javascript and css from create_content
    css_match = re.search(r'<!-- Select2 CSS -->.*?</script>\n</div>', create_content, re.DOTALL) # wait, it's just <!-- Select2 CSS --> ... <script> ... </script>
    
    # Let's just find everything from <!-- Select2 CSS --> to the end
    extras = create_content[create_content.find('<!-- Select2 CSS -->'):].strip()
    
    # In template_content, replace the main-content block with the new main_content_html
    new_page = re.sub(r'<div class="main-content">.*?</footer>\s*</div>', main_content_html.replace('\\', '\\\\'), template_content, flags=re.DOTALL)
    
    # Add Select2 CSS to head
    new_page = new_page.replace('</head>', '<!-- Select2 CSS -->\n<link href="{{ asset(\'assets/libs/select2/css/select2.min.css\') }}" rel="stylesheet" type="text/css" />\n</head>')
    
    # Replace the JS scripts in new_page (at the bottom) with the ones from create.blade.php
    # We'll just replace the Required datatable js and Sweet Alerts js with Select2 js and logic
    
    # find where to inject select2 logic
    # Find App js
    js_logic = """
<!-- Select2 js -->
<script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // Init Select2
        $('.select2').select2({
            width: '100%'
        });

        // AJAX Fetch Data Santri
        $('#nis').on('change', function() {
            var nis = $(this).val();
            if(nis) {
                $.ajax({
                    url: '/beasiswa/santri/' + nis,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        if(data) {
                            $('#ro_nama').val(data.nama_lengkap);
                        } else {
                            $('#ro_nama').val('(Tidak ditemukan)');
                        }
                    },
                    error: function() {
                        $('#ro_nama').val('(Error memuat data)');
                    }
                });
            } else {
                $('#ro_nama').val('(Pilih santri terlebih dahulu)');
            }
        });
    });
</script>
"""
    # Replace datatable JS
    new_page = re.sub(r'<!-- Required datatable js -->.*<script src="\{\{ asset\("assets/js/pages/datatables\.init\.js"\) \}\}\"></script>', js_logic, new_page, flags=re.DOTALL)
    
    # Remove Sweet Alerts js
    new_page = re.sub(r'<!-- Sweet Alerts js -->.*?</script>', '', new_page, flags=re.DOTALL)
    
    # Write back to create.blade.php
    with open('resources/views/beasiswa/create.blade.php', 'w') as f:
        f.write(new_page)
        
    print("create.blade.php generated successfully.")
else:
    print("Could not parse create.blade.php correctly.")
