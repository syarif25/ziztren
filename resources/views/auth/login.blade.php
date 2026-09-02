<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>ZIZTREN - Sistem Pendataan Beasiswa</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Sistem Informasi Pendataan dan Manajemen Beasiswa - ZIZTREN" name="description" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{ asset('assets/images/p2s3.png') }}">

        <!-- Bootstrap Css -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

        <!-- Sweet Alert-->
        <link href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    </head>

    <body class="auth-body-bg">
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

    </body>
</html>
