<!-- resources/views/layouts/app.blade.php -->
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Untree.co">
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">

    <meta name="description" content="" />
    <meta name="keywords" content="bootstrap, bootstrap4" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS and Other Styles -->
    <link href="{{ asset('css/home/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/home/tiny-slider.css') }}" rel="stylesheet">
    <link href="{{ asset('css/home/style.css') }}" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    @stack('header-styles')
    @stack('header-script')

    <title>@yield('title', 'Stonify')</title>
</head>
<style>
a {
    text-decoration: none;
}
</style>
<body>

    <!-- Start Header/Navigation -->
    <nav class="custom-navbar navbar navbar-expand-md navbar-dark bg-dark" arial-label="Furni navigation bar">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">STONIFY<span>.</span></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsFurni" aria-controls="navbarsFurni" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsFurni">
                <ul class="custom-navbar-nav navbar-nav ms-auto mb-2 mb-md-0">
                    <li><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Layanan
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{ route('frontend.shop') }}">Shop</a></li>
                            <li><a class="dropdown-item" href="{{ route('frontend.rekomendasi') }}">Desain</a></li>
                        </ul>
                    </li>
                    <li><a class="nav-link" href="{{ route('frontend.artikel') }}">Artikel</a></li>
                    <li><a class="nav-link" href="{{ route('frontend.contact') }}">Hubungi Kami</a></li>
                </ul>

                <ul class="custom-navbar-cta navbar-nav mb-2 mb-md-0 ms-5">
                    <li><a class="nav-link" href="{{ route('login') }}"><img src="{{ asset('img/user.svg') }}"></a></li>
                    <li class="relative">
                        <a class="nav-link" href="{{ route('frontend.cart') }}">
                            <img src="{{ asset('img/cart.svg') }}" class="w-6 h-6">
                            <span id="cart-count" class="absolute top-0 right-0 inline-flex items-center justify-center w-6 h-6 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                                0
                            </span>
                        </a>
                    </li>

                    <script>
                        function updateCartCount() {
                            fetch('{{ route('cart.count') }}')
                                .then(response => response.json())
                                .then(data => {
                                    document.getElementById('cart-count').innerText = data.count;
                                });
                        }

                        // Auto refresh setiap 10 detik (optional polling)
                        setInterval(updateCartCount, 10000);

                        // Atau panggil ini saat tambah item ke cart
                        // updateCartCount();
                    </script>


                </ul>
            </div>
        </div>
    </nav>
    <!-- End Header/Navigation -->

    @yield('content')

    <!-- Start Footer Section -->
    <footer class="footer-section">
        <div class="container relative">
            <div class="row pt-4">
                <div class="col-lg-6">
                    <p class="mb-2 text-center text-lg-start">Copyright &copy;<script>document.write(new Date().getFullYear());</script>. All Rights Reserved. &mdash; Designed with love by <a href="#">stonify.co</a> Distributed By <a href="https://themewagon.com">Echa Triansah Putri</a></p>
                </div>
            </div>
        </div>
    </footer>
    <!-- End Footer Section -->

    <!-- Scripts -->
    <script src="{{ asset('js/home/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/home/tiny-slider.js') }}"></script>
    <script src="{{ asset('js/home/custom.js') }}"></script>
</body>
</html>
