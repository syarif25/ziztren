import re

# Read the template from beasiswa.blade.php
with open('resources/views/beasiswa.blade.php', 'r') as f:
    template_content = f.read()

# Read the content of create.blade.php
with open('resources/views/beasiswa/create.blade.php', 'r') as f:
    create_content = f.read()

# Extract the main-content block from create_content
match = re.search(r'<div class="page-content">.*<!-- End Page-content -->', create_content, re.DOTALL)
if match:
    main_content_html = match.group(0)
    
    # We also need the javascript and css from create_content
    css_match = re.search(r'<!-- Select2 CSS -->\s*<link href="\{\{ asset\(\'assets/libs/select2/css/select2\.min\.css\'\) \}\}" rel="stylesheet" type="text/css" />', create_content, re.DOTALL)
    js_match = re.search(r'<!-- Select2 js -->.*?</script>', create_content, re.DOTALL)
    
    css_string = css_match.group(0) if css_match else ''
    js_string = js_match.group(0) if js_match else ''
    
    # In template_content, replace the page-content block with the new main_content_html
    new_page = re.sub(r'<div class="page-content">.*<!-- End Page-content -->', main_content_html.replace('\\', '\\\\'), template_content, flags=re.DOTALL)
    
    # Also replace sweetalert stuff with select2 if necessary, or just append it to the head and body
    
    # Add Select2 CSS to head
    new_page = new_page.replace('</head>', css_string + '\n</head>')
    
    # Replace the datatable init script block with select2 js script block
    # Remove datatable stuff
    new_page = re.sub(r'<!-- Required datatable js -->.*<script src="\{\{ asset\("assets/js/pages/datatables\.init\.js"\) \}\}\"></script>', js_string, new_page, flags=re.DOTALL)
    
    # Remove sweetalerts js logic that was injected for datatable
    new_page = re.sub(r'<!-- Sweet Alerts js -->.*?</script>', '', new_page, flags=re.DOTALL)
    
    # Make sure we don't have multiple js_string injected
    with open('resources/views/beasiswa/create.blade.php', 'w') as f:
        f.write(new_page)

    print("create.blade.php regenerated with full HTML layout.")
else:
    print("Could not find page-content in create.blade.php")
