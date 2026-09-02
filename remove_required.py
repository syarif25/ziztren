import re

filepath = 'resources/views/beasiswa/create.blade.php'

with open(filepath, 'r') as f:
    content = f.read()

# Replace all occurrences of required (except for nis which is fine since it's on Tab 1)
# Actually, I can just replace ` required>` with `>` on the lines in Tab 3.
# Or remove ` required` safely.

content = re.sub(r' required>', '>', content)
content = re.sub(r' required>', '>', content)
content = re.sub(r' required>', '>', content)
# just global replacement of " required>" to ">"
content = content.replace(' required>', '>')
content = content.replace(' required', '')

with open(filepath, 'w') as f:
    f.write(content)

print("Removed HTML5 required attributes.")
