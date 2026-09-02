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

logout_html = """
                            <li>
                                <a href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="waves-effect text-danger">
                                    <i class="ri-shut-down-line text-danger"></i>
                                    <span>Logout</span>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                    <!-- Sidebar -->"""

for file in files:
    with open(file, 'r') as f:
        content = f.read()

    # Pattern to replace
    pattern = r'</ul>\s*</div>\s*<!-- Sidebar -->'
    content = re.sub(pattern, logout_html, content)

    with open(file, 'w') as f:
        f.write(content)

print("Done")
