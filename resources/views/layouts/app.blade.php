<!doctype html>
<html lang="en">

    <head>
        <meta charset="utf-8" />
        <title>@yield('title', 'LAZIMY') | LAZIMY</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" href="{{ asset('assets/images/p2s2_fav.png') }}">
        <link rel="icon" type="image/png" href="{{ asset('assets/images/p2s2_fav.png') }}">

        <!-- DataTables -->
        <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

        <!-- Bootstrap Css -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App Css -->
        <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
        <!-- Sweet Alert -->
        <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

        {{-- CSS tambahan per halaman (select2, flatpickr, dll) --}}
        @stack('styles')

        <style>
            /* P2S3 Preloader Styling & Animation */
            #preloader {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: #ffffff;
                z-index: 999999;
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 1;
                visibility: visible;
                transition: opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.4s ease;
            }

            #status {
                position: relative;
                top: auto;
                left: auto;
                transform: none;
                margin: 0;
                text-align: center;
            }

            .p2s3-loader-container {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }

            .p2s3-logo-wrapper {
                position: relative;
                display: inline-block;
                padding: 12px 20px;
                border-radius: 18px;
                background: radial-gradient(circle at center, rgba(15, 156, 243, 0.08) 0%, rgba(255, 255, 255, 0) 70%);
                overflow: hidden;
                animation: p2s3FloatPulse 2.4s ease-in-out infinite;
            }

            .p2s3-anim-logo {
                display: block;
                max-width: 290px;
                width: 80vw;
                height: auto;
                object-fit: contain;
                filter: drop-shadow(0 6px 16px rgba(15, 156, 243, 0.3)) drop-shadow(0 2px 8px rgba(245, 158, 11, 0.25));
            }

            .p2s3-shimmer-sweep {
                position: absolute;
                top: 0;
                left: -150%;
                width: 100%;
                height: 100%;
                background: linear-gradient(
                    90deg,
                    rgba(255, 255, 255, 0) 0%,
                    rgba(255, 255, 255, 0.6) 50%,
                    rgba(255, 255, 255, 0) 100%
                );
                transform: skewX(-25deg);
                animation: p2s3Shimmer 2.2s infinite ease-in-out;
                pointer-events: none;
            }

            .p2s3-progress-track {
                width: 180px;
                height: 4px;
                background: #e2e8f0;
                border-radius: 10px;
                overflow: hidden;
                margin: 22px auto 10px;
                position: relative;
                box-shadow: inset 0 1px 2px rgba(0,0,0,0.06);
            }

            .p2s3-progress-fill {
                position: absolute;
                top: 0;
                left: 0;
                height: 100%;
                border-radius: 10px;
                background: linear-gradient(90deg, #0f9cf3, #f59e0b, #0f9cf3);
                background-size: 200% 100%;
                animation: p2s3ProgressRun 1.5s infinite cubic-bezier(0.65, 0, 0.35, 1), p2s3GradientMove 2s infinite linear;
            }

            .p2s3-loader-subtext {
                font-size: 11px;
                font-weight: 700;
                letter-spacing: 2px;
                color: #64748b;
                margin-top: 4px;
            }

            .p2s3-dots::after {
                content: '';
                display: inline-block;
                animation: p2s3DotAnim 1.6s infinite steps(4, end);
            }

            @keyframes p2s3FloatPulse {
                0%, 100% {
                    transform: translateY(0) scale(1);
                }
                50% {
                    transform: translateY(-8px) scale(1.04);
                }
            }

            @keyframes p2s3Shimmer {
                0% {
                    left: -150%;
                }
                40%, 100% {
                    left: 200%;
                }
            }

            @keyframes p2s3ProgressRun {
                0% {
                    left: -50%;
                    width: 30%;
                }
                50% {
                    left: 25%;
                    width: 60%;
                }
                100% {
                    left: 100%;
                    width: 25%;
                }
            }

            @keyframes p2s3GradientMove {
                0% {
                    background-position: 0% 50%;
                }
                100% {
                    background-position: 200% 50%;
                }
            }

            @keyframes p2s3DotAnim {
                0% { content: ''; }
                25% { content: '.'; }
                50% { content: '..'; }
                75% { content: '...'; }
                100% { content: ''; }
            }
        </style>
    </head>

    <body data-topbar="dark">
        {{-- ======================== PRELOADER ANIMASI LOGO P2S3 ======================== --}}
        <div id="preloader">
            <div id="status">
                <div class="p2s3-loader-container">
                    <div class="p2s3-logo-wrapper">
                        <img src="{{ asset('assets/images/p2s3.png') }}" alt="Logo P2S3" class="p2s3-anim-logo">
                        <div class="p2s3-shimmer-sweep"></div>
                    </div>
                    <div class="p2s3-progress-track">
                        <div class="p2s3-progress-fill"></div>
                    </div>
                    <div class="p2s3-loader-subtext">MEMUAT HALAMAN<span class="p2s3-dots"></span></div>
                </div>
            </div>
        </div>

        <div id="layout-wrapper">

            {{-- ======================== HEADER / TOPBAR ======================== --}}
            <header id="page-topbar">
                <div class="navbar-header">
                    <div class="d-flex">
                        <div class="navbar-brand-box">
                            <a href="{{ route('dashboard') }}" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{ asset('assets/images/p2s3.png') }}" alt="logo" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('assets/images/p2s3.png') }}" alt="logo" height="28" class="me-1">
                                    <span class="fw-bold text-primary" style="font-size: 18px; letter-spacing: 1px;">ZIZTREN</span>
                                </span>
                            </a>
                            <a href="{{ route('dashboard') }}" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="{{ asset('assets/images/p2s3.png') }}" alt="logo" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{ asset('assets/images/p2s3.png') }}" alt="logo" height="28" class="me-1">
                                    <span class="fw-bold text-white" style="font-size: 18px; letter-spacing: 1px;">ZIZTREN</span>
                                </span>
                            </a>
                        </div>
                        <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                            <i class="ri-menu-2-line align-middle"></i>
                        </button>
                    </div>
                </div>
            </header>

            {{-- ======================== SIDEBAR ======================== --}}
            <div class="vertical-menu">
                <div data-simplebar class="h-100">
                    <div class="user-profile text-center mt-3">
                        <div class="">
                            <img src="{{ asset('assets/images/users/user.png') }}" alt="Profile Picture" class="avatar-md rounded-circle">
                        </div>
                        <div class="mt-3">
                            <h4 class="font-size-16 mb-1">{{ Auth::user()->username ?? 'User' }}</h4>
                            <span class="text-muted"><i class="ri-record-circle-line align-middle font-size-14 text-success"></i> Online</span>
                        </div>
                    </div>

                    <div id="sidebar-menu">
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li class="menu-title">Menu</li>
                            <li class="{{ request()->routeIs('dashboard') ? 'mm-active' : '' }}">
                                <a href="{{ route('dashboard') }}" class="waves-effect">
                                    <i class="ri-dashboard-line"></i><span>Dashboard</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('beasiswa.*') ? 'mm-active' : '' }}">
                                <a href="{{ route('beasiswa.index') }}" class="waves-effect">
                                    <i class="ri-file-list-3-line"></i><span>Beasiswa</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('histori-status.*') ? 'mm-active' : '' }}">
                                <a href="{{ route('histori-status.index') }}" class="waves-effect">
                                    <i class="ri-history-line"></i><span>Histori Pengajuan</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('laporan.*') ? 'mm-active' : '' }}">
                                <a href="{{ route('laporan.index') }}" class="waves-effect">
                                    <i class="ri-file-chart-line"></i><span>Laporan & Rekap</span>
                                </a>
                            </li>
                            <li class="{{ request()->routeIs('pengguna.*', 'lembaga.*', 'master-periode.*', 'provinsi.*', 'kota.*', 'kecamatan.*', 'desa.*', 'santri.*') ? 'mm-active' : '' }}">
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-database-2-line"></i><span>Master</span>
                                </a>
                                <ul class="sub-menu" aria-expanded="{{ request()->routeIs('pengguna.*', 'lembaga.*', 'master-periode.*', 'provinsi.*', 'kota.*', 'kecamatan.*', 'desa.*', 'santri.*') ? 'true' : 'false' }}">
                                    <li><a href="{{ route('pengguna.index') }}" class="{{ request()->routeIs('pengguna.*') ? 'active' : '' }}">Pengguna</a></li>
                                    <li><a href="{{ route('lembaga.index') }}" class="{{ request()->routeIs('lembaga.*') ? 'active' : '' }}">Lembaga</a></li>
                                    <li><a href="{{ route('master-periode.index') }}" class="{{ request()->routeIs('master-periode.*') ? 'active' : '' }}">Master Periode</a></li>
                                    <li><a href="{{ route('provinsi.index') }}" class="{{ request()->routeIs('provinsi.*') ? 'active' : '' }}">Provinsi</a></li>
                                    <li><a href="{{ route('kota.index') }}" class="{{ request()->routeIs('kota.*') ? 'active' : '' }}">Kota</a></li>
                                    <li><a href="{{ route('kecamatan.index') }}" class="{{ request()->routeIs('kecamatan.*') ? 'active' : '' }}">Kecamatan</a></li>
                                    <li><a href="{{ route('desa.index') }}" class="{{ request()->routeIs('desa.*') ? 'active' : '' }}">Desa/Kelurahan</a></li>
                                    <li><a href="{{ route('santri.index') }}" class="{{ request()->routeIs('santri.*') ? 'active' : '' }}">Santri</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript:void(0);" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="waves-effect text-danger">
                                    <i class="ri-shut-down-line text-danger"></i><span>Logout</span>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- ======================== MAIN CONTENT ======================== --}}
            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">

                        {{-- Page Title / Breadcrumb --}}
                        @hasSection('page-title')
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">@yield('page-title')</h4>
                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            @yield('breadcrumb')
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Konten utama tiap halaman --}}
                        @yield('content')

                    </div>
                </div>

                {{-- Footer --}}
                <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                <script>document.write(new Date().getFullYear())</script> © Ziztren.
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <div class="rightbar-overlay"></div>

        {{-- Modal per halaman --}}
        @yield('modals')

        {{-- ======================== JAVASCRIPT GLOBAL ======================== --}}
        <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>

        <!-- DataTables JS -->
        <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

        <!-- Sweet Alerts -->
        <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

        <!-- App JS -->
        <script src="{{ asset('assets/js/app.js') }}"></script>

        {{-- Script Transisi Halaman Preloader P2S3 --}}
        <script>
            (function() {
                function hideP2s3Preloader() {
                    var preloader = document.getElementById('preloader');
                    if (preloader) {
                        preloader.style.opacity = '0';
                        preloader.style.visibility = 'hidden';
                        preloader.style.pointerEvents = 'none';
                        setTimeout(function() {
                            preloader.style.display = 'none';
                        }, 400);
                    }
                }

                // Sembunyikan preloader setelah halaman selesai dimuat
                if (document.readyState === 'complete') {
                    hideP2s3Preloader();
                } else {
                    window.addEventListener('load', hideP2s3Preloader);
                    // Failsafe timer (maksimal 2.5 detik agar user tidak tertahan jika ada resource lambat)
                    setTimeout(hideP2s3Preloader, 2500);
                }

                // Tampilkan preloader animasi saat user mengklik tautan/menu untuk membuka halaman baru
                document.addEventListener('click', function(e) {
                    var anchor = e.target.closest('a');
                    if (!anchor) return;

                    var href = anchor.getAttribute('href');
                    var target = anchor.getAttribute('target');
                    var hasDownload = anchor.hasAttribute('download');

                    if (
                        href &&
                        href !== '#' &&
                        !href.startsWith('#') &&
                        !href.startsWith('javascript:') &&
                        !href.includes('/pdf') &&
                        !href.includes('/export') &&
                        target !== '_blank' &&
                        !hasDownload &&
                        !e.ctrlKey && !e.metaKey && !e.shiftKey
                    ) {
                        var preloader = document.getElementById('preloader');
                        if (preloader) {
                            preloader.style.display = 'flex';
                            // Force reflow
                            void preloader.offsetHeight;
                            preloader.style.opacity = '1';
                            preloader.style.visibility = 'visible';
                            preloader.style.pointerEvents = 'all';
                        }
                    }
                });
            })();
        </script>

        {{-- Script tambahan per halaman --}}
        @stack('scripts')

    </body>

</html>
