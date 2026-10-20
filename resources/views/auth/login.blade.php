<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>LAZIMY - Sistem Informasi Pengajuan Beasiswa Santri P2S3</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Sistem Informasi Pengajuan dan Pendataan Beasiswa Santri Pondok Pesantren Salafiyah Syafi'iyah Sukorejo (LAZIMY). Mempermudah proses pengajuan, verifikasi, dan pencairan beasiswa santri." />
        <meta name="keywords" content="lazimy, beasiswa santri, p2s3 sukorejo, lazis p2s3, beasiswa sukorejo, pendataan beasiswa, salafiyah syafiiyah" />
        <meta name="author" content="Pondok Pesantren Salafiyah Syafi'iyah Sukorejo" />
        <meta name="robots" content="index, follow" />

        <!-- Open Graph / Facebook / WhatsApp Preview -->
        <meta property="og:type" content="website" />
        <meta property="og:url" content="https://lazimy.p2s3.com/" />
        <meta property="og:title" content="LAZIMY - Sistem Informasi Pengajuan Beasiswa Santri P2S3" />
        <meta property="og:description" content="Layanan resmi pengajuan, verifikasi, dan penyaluran beasiswa santri Pondok Pesantren Salafiyah Syafi'iyah Sukorejo." />
        <meta property="og:image" content="{{ asset('assets/images/logo-lazimy.png') }}" />

        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" content="LAZIMY - Sistem Informasi Pengajuan Beasiswa Santri P2S3" />
        <meta name="twitter:description" content="Layanan resmi pengajuan, verifikasi, dan penyaluran beasiswa santri Pondok Pesantren Salafiyah Syafi'iyah Sukorejo." />
        <meta name="twitter:image" content="{{ asset('assets/images/logo-lazimy.png') }}" />

        <!-- Canonical URL -->
        <link rel="canonical" href="https://lazimy.p2s3.com/" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/images/p2s2_fav.png') }}">
        <link rel="icon" type="image/png" href="{{ asset('assets/images/p2s2_fav.png') }}">

        <!-- Bootstrap Css -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

        <!-- Sweet Alert-->
        <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />

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

    <body class="auth-body-bg">
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

        <div class="bg-overlay"></div>
        <div class="wrapper-page">
            <div class="container-fluid p-0">
                <div class="card">
                    <div class="card-body">

                        <div class="text-center mt-4">
                            <div class="mb-3">
                                <a href="#" class="auth-logo">
                                    <img src="{{ asset('assets/images/p2s3.png') }}" height="60" class="logo-dark mx-auto" alt="">
                                    <img src="{{ asset('assets/images/p2s3.png') }}" height="60" class="logo-light mx-auto" alt="">
                                </a>
                            </div>
                        </div>
    
                        <h4 class="text-muted text-center font-size-18"><b>Sign In</b></h4>
    
                            <form class="form-horizontal mt-3" action="{{ route('login.post') }}" method="POST">
                                @csrf
    
                                <div class="form-group mb-3 row">
                                    <div class="col-12">
                                        <input class="form-control @error('username') is-invalid @enderror" type="text" name="username" value="{{ old('username') }}" required="" placeholder="Username">
                                        @error('username')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
    
                                <div class="form-group mb-3 row">
                                    <div class="col-12">
                                        <input class="form-control @error('password') is-invalid @enderror" type="password" name="password" required="" placeholder="Password">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>  
                                </div>
    
                                <div class="form-group mb-3 row">
                                    <div class="col-12">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="remember" class="custom-control-input" id="customCheck1" {{ old('remember') ? 'checked' : '' }}>
                                            <label class="form-label ms-1" for="customCheck1">Remember me</label>
                                        </div>
                                    </div>
                                </div>
    
                                <div class="form-group mb-3 text-center row mt-3 pt-1">
                                    <div class="col-12">
                                        <button class="btn btn-info w-100 waves-effect waves-light" type="submit">Log In</button>
                                    </div>
                                </div>
    
                            </form>
                        </div>
                        <!-- end -->
                    </div>
                    <!-- end cardbody -->
                </div>
                <!-- end card -->
            </div>
            <!-- end container -->
        </div>
        <!-- end -->

        <!-- JAVASCRIPT -->
        <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
        <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
        <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>

        <!-- Sweet Alerts js -->
        <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>

        @if ($errors->any())
        <script>
            Swal.fire({
                title: 'Login Gagal!',
                text: '{{ $errors->first() }}',
                icon: 'error',
                confirmButtonColor: '#5664d2',
                confirmButtonText: 'Coba Lagi'
            });
        </script>
        @endif

        <script src="{{ asset('assets/js/app.js') }}"></script>

        {{-- Script Preloader P2S3 --}}
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

                if (document.readyState === 'complete') {
                    hideP2s3Preloader();
                } else {
                    window.addEventListener('load', hideP2s3Preloader);
                    setTimeout(hideP2s3Preloader, 2500);
                }

                // Tampilkan preloader saat form login disubmit
                var form = document.querySelector('form');
                if (form) {
                    form.addEventListener('submit', function() {
                        var preloader = document.getElementById('preloader');
                        if (preloader) {
                            preloader.style.display = 'flex';
                            void preloader.offsetHeight;
                            preloader.style.opacity = '1';
                            preloader.style.visibility = 'visible';
                            preloader.style.pointerEvents = 'all';
                        }
                    });
                }
            })();
        </script>

    </body>
</html>
