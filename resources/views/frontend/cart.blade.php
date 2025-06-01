@extends('layouts.master')

@section('title', 'Cart - Stonify')

@section('content')
<div class="hero">
    <div class="container">
        <h1>Keranjang Belanja</h1>
    </div>
</div>

<div class="container mt-5">
    <table class="table">
        <thead>
            <tr>
                <th>Foto</th>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Hapus</th>
            </tr>
        </thead>
        <tbody>
            @if ($cart->count() > 0)
                @foreach ($cart as $item)
                    <tr data-id="{{ $item->product_id }}">
                        <td><img src="{{ asset('storage/' . $item->product->foto_produk) }}" width="50"></td>
                        <td>{{ $item->product->nama_produk }}</td>
                        <td class="item-price">Rp. {{ number_format($item->product->harga, 2) }}</td>
                        <td>
                            <input type="number" value="{{ $item->quantity }}" class="form-control update-cart" data-id="{{ $item->product_id }}" min="1">
                        </td>
                        <td class="item-total">Rp. {{ number_format($item->product->harga * $item->quantity, 2) }}</td>
                        <td>
                            <button class="btn btn-danger remove-from-cart" data-id="{{ $item->product_id }}">X</button>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="6" class="text-center">Keranjang masih kosong</td>
                </tr>
            @endif
        </tbody>

        <tfoot>
            <tr>
                <td colspan="4"><strong>Total Keseluruhan:</strong></td>
                <td class="cart-total">Rp. {{ number_format($cart->sum(fn($item) => $item->product->harga * $item->quantity), 2) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    <button class="btn btn-primary" onclick="window.location='{{ url('/checkout') }}'">Lanjut ke pembayaran</button>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
    // Update jumlah produk ketika input berubah
    $(document).on("change", ".update-cart", function () {
        let id = $(this).data("id");
        let quantity = $(this).val();

        // Pastikan hanya angka yang valid, jika tidak set nilai menjadi 1
        if (isNaN(quantity) || quantity <= 0) {
            quantity = 1;
            $(this).val(quantity); // Reset input menjadi 1
        }

        $.ajax({
            url: "{{ url('/update-cart') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: id,
                quantity: quantity
            },
            success: function (response) {
                if (response.cart) {
                    updateCartUI(response.cart); // Update UI keranjang dengan data terbaru
                    updateCartTotal(response.total); // Update total keseluruhan
                }
            }
        });
    });

    // Hapus produk
    $(document).on("click", ".remove-from-cart", function () {
        let id = $(this).data("id");

        $.ajax({
            url: "{{ url('/remove-from-cart') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                product_id: id
            },
            success: function (response) {
                if (response.cart) {
                    updateCartUI(response.cart);
                    updateCartTotal(response.total);
                }
            }
        });
    });

    function updateCartUI(cart) {
        let tbody = $("tbody");
        tbody.empty(); // Kosongkan isi tabel

        $.each(cart, function (id, item) {
            let row = `
                <tr data-id="${item.product_id}">
                    <td><img src="{{ asset('storage/') }}/${item.product.foto_produk}" width="50"></td>
                    <td>${item.product.nama_produk}</td>
                    <td class="item-price">Rp. ${parseFloat(item.product.harga).toLocaleString("id-ID", { minimumFractionDigits: 2 })}</td>
                    <td>
                        <input type="number" value="${item.quantity}" class="form-control update-cart" data-id="${item.product_id}" min="1">
                    </td>
                    <td class="item-total">Rp. ${(item.product.harga * item.quantity).toLocaleString("id-ID", { minimumFractionDigits: 2 })}</td>
                    <td>
                        <button class="btn btn-danger remove-from-cart" data-id="${item.product_id}">X</button>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
    }

    function updateCartTotal(total) {
        $(".cart-total").text(`Rp. ${total.toLocaleString("id-ID", { minimumFractionDigits: 2 })}`);
    }
});
</script>

@endsection
