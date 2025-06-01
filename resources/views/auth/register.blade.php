<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iofrm - Register</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/login/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/login/fontawesome-all.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/login/iofrm-style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/login/iofrm-theme27.css') }}">
</head>
<body>
    <div class="form-body without-side">
        <div class="website-logo">
            <a href="{{ url('/') }}">
                <div class="logo">
                    <img class="logo-size" src="{{ asset('img/logo-light.svg') }}" alt="">
                </div>
            </a>
        </div>
        <div class="iofrm-layout">
            <div class="img-holder">
                <div class="bg"></div>
                <div class="info-holder">
                    <img src="{{ asset('img/graphic8.svg') }}" alt="">
                </div>
            </div>
            <div class="form-holder">
                <div class="form-content">
                    <div class="form-items">
                        <div class="form-icon">
                            <div class="icon-holder"><img src="{{ asset('img/icon1.svg') }}" alt=""></div>
                        </div>
                        <h3 class="form-title-center">Daftar Sekarang</h3>
                        <form action="{{ route('register') }}" method="POST">
                            @csrf
                            <!-- Name Field -->
                            <input class="form-control" type="text" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required autofocus>
                            
                            <!-- Email Field -->
                            <input class="form-control" type="email" name="email" placeholder="Email Address" value="{{ old('email') }}" required>
                            
                            <!-- Password Field -->
                            <input class="form-control" type="password" name="password" placeholder="Password" required>
                            
                            <!-- Confirm Password Field -->
                            <input class="form-control" type="password" name="password_confirmation" placeholder="Konfirmasi Password" required>
                            
                            <div class="form-button">
                                <button id="submit" type="submit" class="ibtn ibtn-full">Daftar</button>
                            </div>
                        </form>
                        <div class="text-center mt-3">
                            <p>Sudah punya akun? <a href="{{ route('login') }}">Login</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/login/jquery.min.js') }}"></script>
    <script src="{{ asset('js/login/popper.min.js') }}"></script>
    <script src="{{ asset('js/login/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/login/main.js') }}"></script>
</body>
</html>
