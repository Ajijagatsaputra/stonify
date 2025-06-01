@extends('layouts.master')

@section('title', 'Home - Stonify')

@section('content')

<!-- Start Hero Section -->
<div class="hero">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-5">
                <div class="intro-excerpt">
                    <h1>Shop</h1>
                </div>
            </div>
            <div class="col-lg-7"></div>
        </div>
    </div>
</div>
<!-- End Hero Section -->

<div class="untree_co-section product-section before-footer-section">
    <div class="container">
        <div class="row">
            @foreach($produks as $product)
                <div class="col-12 col-md-4 col-lg-3 mb-5">
                    <a class="product-item" href="#">
                        <img src="{{ asset('storage/'.$product->foto_produk) }}" class="img-fluid product-thumbnail" alt="{{ $product->nama_produk }}">
                        <h3 class="product-title">{{ $product->nama_produk }}</h3>
                        <strong class="product-price">Rp.{{ number_format($product->harga, 2) }}</strong>
                        <span class="icon-cross add-to-cart" data-id="{{ $product->id }}" data-name="{{ $product->nama_produk }}" data-price="{{ $product->harga }}" data-image="{{ asset('storage/'.$product->foto_produk) }}">
                            <img src="{{ asset('img/cross.svg') }}" class="img-fluid" alt="Close Icon">
                        </span>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.add-to-cart').forEach(button => {
            button.addEventListener('click', async function (e) {
                e.preventDefault();

                let product = {
                    id: this.dataset.id,
                    name: this.dataset.name,
                    price: this.dataset.price,
                    image: this.dataset.image
                };

                try {
                    let response = await fetch("{{ route('cart.add') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
                        },
                        body: JSON.stringify(product)
                    });

                    if (response.status === 401) {
                        // Jika user belum login, arahkan ke halaman login
                        window.location.href = "/login";
                        return;
                    }

                    let data = await response.json();
                    alert(data.message);
                } catch (error) {
                    console.error("Error:", error);
                }
            });
        });
    });
</script>



@endsection
