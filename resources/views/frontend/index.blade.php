<!-- resources/views/home/index.blade.php -->
@extends('layouts.master')

@section('title', 'Home - Stonify')

@section('content')
    <!-- Start Hero Section -->
    <div class="hero">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-5">
                    <div class="intro-excerpt">
                        <h1>Toko Batu Alam <span class="d-block">CV Mulya Jaya Alam</span></h1>
                        <p class="mb-4">Jadikan halaman Anda lebih indah dengan batu alam berkualitas. Temukan berbagai
                            pilihan desain yang cocok untuk segala kebutuhan. Membuat desain sendiri sesuai keinginan kamu
                        </p>
                        <p><a href="#" class="btn btn-secondary me-2">Belanja Sekarang</a><a href="#"
                                class="btn btn-white-outline">Jelajahi</a></p>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="hero-img-wrap">
                        <img src="img/mja.png" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Hero Section -->

    <!-- Start Product Section -->
    <div class="product-section">
        <div class="container">
            <div class="row">

                <!-- Start Column 1 -->
                <div class="col-md-12 col-lg-3 mb-5 mb-lg-0">
                    <h2 class="mb-4 section-title">Batu Alam Dengan Kualitas terbaik.</h2>
                    <p class="mb-4">Dapatkan kualitas terbaik hanya di stonify. </p>
                    <p><a href="#" class="btn">Jelajahi</a></p>
                </div>
                <!-- End Column 1 -->

                <!-- Start Column 2 -->
                <!-- Start Column 2 -->
                @foreach ($produks->take(3) as $product)
                    <div class="col-12 col-md-4 col-lg-3 mb-5 mb-md-0">
                        <a class="product-item" href="#">
                            <!-- Display Product Image -->
                            <img src="{{ asset('storage/' . $product->foto_produk) }}" class="img-fluid product-thumbnail"
                                alt="{{ $product->nama_produk }}">

                            <!-- Product Title -->
                            <h3 class="product-title">{{ $product->nama_produk }}</h3>

                            <!-- Product Price -->
                            <strong class="product-price">Rp.{{ number_format($product->harga, 2) }}</strong>

                            <!-- Cross Icon -->
                            <span class="icon-cross">
                                <img src="{{ asset('img/cross.svg') }}" class="img-fluid" alt="Close Icon">
                            </span>
                        </a>
                    </div>
                @endforeach


            </div>
            <!-- End Column 2 -->

        </div>
    </div>
    </div>
    <!-- End Product Section -->

    <!-- Start Why Choose Us Section -->
    <div class="why-choose-section">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-6">
                    <h2 class="section-title">Mengapa Memilih Kami</h2>
                    <p>Kami menawarkan batu alam berkualitas terbaik dengan layanan terpercaya untuk memenuhi kebutuhan
                        Anda. Jadikan proyek Anda lebih berkelas dengan kami.</p>

                    <div class="row my-5">
                        <div class="col-6 col-md-6">
                            <div class="feature">
                                <div class="icon">
                                    <img src="img/truck.svg" alt="Pengiriman Cepat" class="imf-fluid">
                                </div>
                                <h3>Pengiriman Cepat &amp; Gratis</h3>
                                <p>Kami menyediakan layanan pengiriman cepat dan gratis untuk memastikan produk sampai tepat
                                    waktu.</p>
                            </div>
                        </div>

                        <div class="col-6 col-md-6">
                            <div class="feature">
                                <div class="icon">
                                    <img src="img/bag.svg" alt="Belanja Mudah" class="imf-fluid">
                                </div>
                                <h3>Belanja Mudah</h3>
                                <p>Platform kami dirancang untuk memudahkan Anda berbelanja dengan pengalaman yang
                                    menyenangkan.</p>
                            </div>
                        </div>

                        <div class="col-6 col-md-6">
                            <div class="feature">
                                <div class="icon">
                                    <img src="img/support.svg" alt="Dukungan 24/7" class="imf-fluid">
                                </div>
                                <h3>Dukungan 24/7</h3>
                                <p>Tim kami siap membantu Anda kapan saja dengan layanan pelanggan terbaik.</p>
                            </div>
                        </div>

                        <div class="col-6 col-md-6">
                            <div class="feature">
                                <div class="icon">
                                    <img src="img/return.svg" alt="Pengembalian Mudah" class="imf-fluid">
                                </div>
                                <h3>Pengembalian Tanpa Ribet</h3>
                                <p>Kami memberikan kebijakan pengembalian yang mudah untuk kenyamanan Anda.</p>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="img-wrap">
                        <img src="img/sample.jpg" alt="Gambar Mengapa Memilih Kami" class="img-fluid">
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- End Why Choose Us Section -->
    <!-- Start Testimonial Slider -->
    <div class="testimonial-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 mx-auto text-center">
                    <h2 class="section-title">Ulasan</h2>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="testimonial-slider-wrap text-center">

                        <div id="testimonial-nav">
                            <span class="prev" data-controls="prev"><span class="fa fa-chevron-left"></span></span>
                            <span class="next" data-controls="next"><span class="fa fa-chevron-right"></span></span>
                        </div>

                        <div class="testimonial-slider">

                            <div class="item">
                                <div class="row justify-content-center">
                                    <div class="col-lg-8 mx-auto">

                                        <div class="testimonial-block text-center">
                                            <blockquote class="mb-5">
                                                <p>&ldquo;Sangat mudah dan praktis.&rdquo;</p>
                                            </blockquote>

                                            <div class="author-info">
                                                <div class="author-pic">
                                                    <img src="img/person-1.png" alt="Maria Jones" class="img-fluid">
                                                </div>
                                                <h3 class="font-weight-bold">Hana</h3>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- END item -->

                            <div class="item">
                                <div class="row justify-content-center">
                                    <div class="col-lg-8 mx-auto">

                                        <div class="testimonial-block text-center">
                                            <blockquote class="mb-5">
                                                <p>&ldquo;Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio
                                                    quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate
                                                    velit imperdiet dolor tempor tristique. Pellentesque habitant morbi
                                                    tristique senectus et netus et malesuada fames ac turpis egestas.
                                                    Integer convallis volutpat dui quis scelerisque.&rdquo;</p>
                                            </blockquote>

                                            <div class="author-info">
                                                <div class="author-pic">
                                                    <img src="img/person-1.png" alt="Maria Jones" class="img-fluid">
                                                </div>
                                                <h3 class="font-weight-bold">Maria Jones</h3>
                                                <span class="position d-block mb-3">CEO, Co-Founder, XYZ Inc.</span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- END item -->

                            <div class="item">
                                <div class="row justify-content-center">
                                    <div class="col-lg-8 mx-auto">

                                        <div class="testimonial-block text-center">
                                            <blockquote class="mb-5">
                                                <p>&ldquo;Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio
                                                    quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate
                                                    velit imperdiet dolor tempor tristique. Pellentesque habitant morbi
                                                    tristique senectus et netus et malesuada fames ac turpis egestas.
                                                    Integer convallis volutpat dui quis scelerisque.&rdquo;</p>
                                            </blockquote>

                                            <div class="author-info">
                                                <div class="author-pic">
                                                    <img src="img/person-1.png" alt="Maria Jones" class="img-fluid">
                                                </div>
                                                <h3 class="font-weight-bold">Maria Jones</h3>
                                                <span class="position d-block mb-3">CEO, Co-Founder, XYZ Inc.</span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- END item -->

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Product Section -->
@endsection
