import os
import re

directory = 'resources/views'

pattern = re.compile(
    r'<div class="user-profile text-center mt-3">\s*<div class="">\s*<img src=.*? alt="" class="avatar-md rounded-circle">\s*</div>\s*<div class="mt-3">\s*<h4 class="font-size-16 mb-1">.*?</h4>',
    re.DOTALL
)

replacement = """<div class="user-profile text-center mt-3">
                        <div class="">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username ?? 'User') }}&background=random&color=fff" alt="" class="avatar-md rounded-circle">
                        </div>
                        <div class="mt-3">
                            <h4 class="font-size-16 mb-1">{{ Auth::user()->username ?? 'User' }}</h4>"""

# Walk through all blade files
count = 0
for root, dirs, files in os.walk(directory):
    for file in files:
        if file.endswith('.blade.php'):
            filepath = os.path.join(root, file)
            with open(filepath, 'r') as f:
                content = f.read()
            
            new_content = pattern.sub(replacement, content)
            
            if new_content != content:
                with open(filepath, 'w') as f:
                    f.write(new_content)
                print(f"Updated {filepath}")
                count += 1

print(f"Total files updated: {count}")
