@extends('layouts.master')
@push('header-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

@endpush

@push('header-script')
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

@endpush
@section('title', 'Checkout - Stonify')

@section('content')

<!-- Start Hero Section -->
<div class="hero">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-lg-5">
                <div class="intro-excerpt">
                    <h1>Checkout</h1>
                </div>
            </div>
            <div class="col-lg-7">
                <!-- Additional Content -->
            </div>
        </div>
    </div>
</div>
<!-- End Hero Section -->

<div class="untree_co-section">
    <div class="container">
        <form id="checkout-form">
            <div class="row mb-5">
                <div class="row">
                    <div class="col-md-6 mb-5 mb-md-0">
                        <h2 class="h3 mb-3 text-black">Billing Details</h2>
                        <div class="p-3 p-lg-5 border bg-white">
                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="c_fname" class="text-black">Nama Depan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="c_fname" name="c_fname" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="c_lname" class="text-black">Nama Belakang <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="c_lname" name="c_lname" required>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-12">
                                    <label for="c_address" class="text-black">Alamat <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="c_address" name="c_address" placeholder="Street address" required>
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <input type="text" class="form-control" placeholder="Apartment, suite, unit etc. (optional)">
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6">
                                    <label for="c_state_country" class="text-black">State / Country <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="c_state_country" name="c_state_country" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="c_postal_zip" class="text-black">Kode Pos <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="c_postal_zip" name="c_postal_zip" required>
                                </div>
                            </div>

                            <div class="form-group row mb-5">
                                <div class="col-md-6">
                                    <label for="c_email_address" class="text-black">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="c_email_address" name="c_email_address" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="c_phone" class="text-black">No.HP <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="c_phone" name="c_phone" placeholder="Phone Number" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="c_ship_different_address" class="text-black" data-bs-toggle="collapse" href="#ship_different_address" role="button" aria-expanded="false" aria-controls="ship_different_address">
                                    <input type="checkbox" value="1" id="c_ship_different_address"> Ship To A Different Address?
                                </label>
                                <div class="collapse" id="ship_different_address">
                                    <div class="py-2">
                                        <!-- Ship to Different Address form -->
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="c_order_notes" class="text-black">Order Notes</label>
                                <textarea name="c_order_notes" id="c_order_notes" cols="30" rows="5" class="form-control" placeholder="Write your notes here..."></textarea>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">

                        <div class="row mb-5">
                            <div class="col-md-12">
                                <h2 class="h3 mb-3 text-black">Pesananmu</h2>
                                <div class="p-3 p-lg-5 border bg-white">
                                    <table class="table site-block-order-table mb-5">
                                        <thead>
                                            <tr>
                                                <th>Produk</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cart as $item)
                                            <tr>
                                                <td>{{ $item->product->nama_produk }} <strong class="mx-2">x</strong> {{ $item->quantity }}</td>
                                                <td>Rp. {{ number_format($item->product->harga * $item->quantity, 2) }}</td>
                                            </tr>
                                            @endforeach
                                            <tr>
                                                <td class="text-black font-weight-bold"><strong>Total</strong></td>
                                                <td class="text-black">
                                                    Rp. {{ number_format($cart->sum(fn($item) => $item->product->harga * $item->quantity), 2) }}
                                                </td>
                                            </tr>
                                            <input type="text" name="amount" value="{{ $cart->sum(fn($item) => $item->product->harga * $item->quantity) }}" hidden id="amount">
                                        </tbody>
                                    </table>

                                    <!-- Payment Methods -->
                                    <div class="border p-3 mb-3">
                                        <h3 class="h6 mb-0"><a class="d-block" data-bs-toggle="collapse" href="#collapsebank" role="button" aria-expanded="false" aria-controls="collapsebank">Direct Bank Transfer</a></h3>
                                        <div class="collapse" id="collapsebank">
                                            <div class="py-2">
                                                <p class="mb-0">Silakan transfer ke rekening BCA kami. Gunakan ID pesanan Anda sebagai referensi pembayaran. Pesanan Anda tidak akan dikirim hingga dana diterima.</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Select Payment Method -->
                                    <div class="form-group">
                                        <label for="payment_method" class="text-black">Metode Pembayaran <span class="text-danger">*</span></label>
                                        <select name="payment_method" id="payment_method" class="form-control" required>
                                            <option value="">-- Pilih Metode Pembayaran --</option>
                                            @php
                                                $metode_pembayaran = \App\Models\MetodePembayaran::where('status', true)->get();
                                            @endphp
                                            @foreach ($metode_pembayaran as $item)
                                                <option value="{{ $item->kode }}">{{ $item->nama }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group mt-4">
                                        <button type="submit" id="submit" class="btn btn-black btn-lg py-3 btn-block">Place Order</button>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>

    $(document).ready(function() {
        $('#checkout-form').submit(function (e) {
            e.preventDefault();

            let formData = $(this).serialize();

            $('#submit').html('Processing...').prop('disabled', true);

            if ($('#payment_method').val() == 'bank_transfer') {

                $.ajax({
                    url: '{{ route('checkout.cod') }}',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        // Arahkan ke halaman pesanan setelah berhasil
                        window.location.href = '{{ route('pesanan.index') }}';
                    },
                    error: function (xhr) {
                        $('#submit').html('Place Order').prop('disabled', false);
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function (key, value) {
                                toastr.error(value);
                            });
                        } else {
                            toastr.error("Terjadi kesalahan.");
                        }
                    }
                });
            } else {
            $.ajax({
                url: '{{ route('payment.checkout') }}',
                type: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function (response) {
                    $('#submit').html('Place Order').prop('disabled', false);

                    const snapToken = response.snap_token;
                    const orderId = response.order_id;

                    // Panggil Midtrans Snap
                    executeSnap(snapToken);
                },
                error: function (xhr) {
                    $('#submit').html('Place Order').prop('disabled', false);

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function (key, value) {
                            toastr.error(value);
                        });
                    } else {
                        toastr.error("Terjadi kesalahan.");
                    }
                    }
                });
            }
        });

        function executeSnap(token) {
            snap.pay(token, {
                onSuccess: function(result) {
                    window.location.href = '{{ route('pesanan.index') }}';
                },
                onPending: function(result) {
                    window.location.href = '{{ route('pesanan.index') }}';
                },
                onError: function(result) {
                    toastr.error("Pembayaran gagal!");
                },
                onClose: function() {
                    toastr.warning("Pembayaran dibatalkan.");
                }
            });
        }
    });
</script>


@endsection
