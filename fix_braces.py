import os
import re

views_dir = 'resources/views'
blade_files = [f for f in os.listdir(views_dir) if f.endswith('.blade.php')]

for filename in blade_files:
    filepath = os.path.join(views_dir, filename)
    with open(filepath, 'r') as f:
        content = f.read()
    
    # Fix the routing syntax that got stripped of one set of braces
    # Look for { route(...) } and replace with {{ route(...) }}
    new_content = re.sub(r'\{\s*route\((.*?)\)\s*\}', r'{{ route(\1) }}', content)
    
    if new_content != content:
        with open(filepath, 'w') as f:
            f.write(new_content)
        print(f"Fixed braces in {filename}")
