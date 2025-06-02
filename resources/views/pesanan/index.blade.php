<x-app-layout>

    @push('script-header')
    {{-- Datatables --}}
   <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.dataTables.js"></script>
    <script src="https://cdn.datatables.net/scroller/2.4.3/js/dataTables.scroller.min.js"></script>

   @endpush




    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Pesanan') }}
            </h2>

        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title mb-0">Daftar Pesanan</h3>

                              </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                              <table class="table" id="datatable">
                                  <thead>
                                      <tr>
                                        <th>#</th>
                                        <th>Order ID</th>
                                        <th>Nama Customer</th>
                                        <th>Total Harga</th>
                                        <th>Metode Pembayaran</th>
                                        <th>Status Pesanan</th>
                                        <th>Dibuat Pada</th>
                                        <th>Aksi</th>
                                      </tr>
                                  </thead>
                              </table>
                            </div>
                            <!-- /.card-body -->
                          </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Pesanan -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Pesanan</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close" style="margin-left: auto;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 mb-4">
                            <div class="tracking-status">
                                <div class="tracking-line"></div>
                                <div class="tracking-steps">
                                    <div class="step" id="step-pending">
                                        <div class="step-icon">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <div class="step-text">Menunggu Pembayaran</div>
                                    </div>
                                    <div class="step" id="step-processing">
                                        <div class="step-icon">
                                            <i class="fas fa-box"></i>
                                        </div>
                                        <div class="step-text">Pesanan Diproses</div>
                                    </div>
                                    <div class="step" id="step-shipping">
                                        <div class="step-icon">
                                            <i class="fas fa-shipping-fast"></i>
                                        </div>
                                        <div class="step-text">Dalam Pengiriman</div>
                                    </div>
                                    <div class="step" id="step-delivered">
                                        <div class="step-icon">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                        <div class="step-text">Pesanan Selesai</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <style>
                            .tracking-status {
                                position: relative;
                                padding: 20px 0;
                            }

                            .tracking-line {
                                position: absolute;
                                top: 50%;
                                left: 40px;
                                right: 40px;
                                height: 3px;
                                background: #e0e0e0;
                                transform: translateY(-50%);
                                z-index: 1;
                            }

                            .tracking-steps {
                                display: flex;
                                justify-content: space-between;
                                position: relative;
                                z-index: 2;
                            }

                            .step {
                                text-align: center;
                                padding: 0 20px;
                            }

                            .step-icon {
                                width: 50px;
                                height: 50px;
                                background: #f8f9fa;
                                border: 3px solid #e0e0e0;
                                border-radius: 50%;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                margin: 0 auto 10px;
                                transition: all 0.3s ease;
                            }

                            .step-icon i {
                                font-size: 20px;
                                color: #6c757d;
                                transition: all 0.3s ease;
                            }

                            .step-text {
                                font-size: 14px;
                                color: #6c757d;
                                transition: all 0.3s ease;
                            }

                            .step.active .step-icon {
                                background: #28a745;
                                border-color: #28a745;
                            }

                            .step.active .step-icon i {
                                color: white;
                            }

                            .step.active .step-text {
                                color: #28a745;
                                font-weight: bold;
                            }

                            /* Animation classes - only for current status */
                            .step.active[data-current="true"] .fa-clock {
                                animation: fa-spin 2s infinite linear;
                            }

                            .step.active[data-current="true"] .fa-box {
                                animation: fa-bounce 1s infinite;
                            }

                            .step.active[data-current="true"] .fa-shipping-fast {
                                animation: fa-shake 2.5s infinite;
                            }

                            .step.active[data-current="true"] .fa-check-circle {
                                animation: fa-beat 1s infinite;
                            }

                            /* Animation keyframes */
                            @keyframes fa-spin {
                                0% { transform: rotate(0deg); }
                                100% { transform: rotate(360deg); }
                            }

                            @keyframes fa-bounce {
                                0%, 100% { transform: translateY(0); }
                                50% { transform: translateY(-5px); }
                            }

                            @keyframes fa-shake {
                                0%, 100% { transform: translateX(0); }
                                25% { transform: translateX(-3px); }
                                75% { transform: translateX(3px); }
                            }

                            @keyframes fa-beat {
                                0%, 100% { transform: scale(1); }
                                50% { transform: scale(1.2); }
                            }
                        </style>

                        <div class="col-md-12 mt-3">
                            <h6>Informasi Akun</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td>Nama</td>
                                    <td id="akun-name">-</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td id="akun-email">-</td>
                                </tr>
                            </table>
                        </div>



                        <div class="col-md-6 mt-3">
                            <h6>Informasi Penerima</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td>Nama</td>
                                    <td id="penerima-name">-</td>
                                </tr>
                                <tr>
                                    <td>Telepon</td>
                                    <td id="penerima-phone">-</td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td id="penerima-email">-</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6 mt-3">
                            <h6>Informasi Pesanan</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td>Order ID</td>
                                    <td id="order-id">-</td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td id="order-status">-</td>
                                </tr>
                                <tr>
                                    <td>Metode Pembayaran</td>
                                    <td id="payment-method">-</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Alamat Pengiriman</h6>
                            <p id="shipping-address">-</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Detail Produk</h6>
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody id="product-list">
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-right">Total:</th>
                                        <th id="order-total">-</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    @if (auth()->user()->role->name != 'user')

                    <a id="proses-pesanan" href="javascript:void(0)" data-toggle="tooltip" data-id="" data-original-title="Proses" class="proses btn btn-success btn-xs">
                    </a>
                    @endif
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>


    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            $('#datatable').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "{{ route('pesanan.index') }}",
                    "type": "GET"
                },
                "columns": [
                    {
                        data: 'id',
                        name: 'id',
                        render: function (data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    { data: 'order_id', name: 'order_id' },
                    { data: 'nama_customer', name: 'nama_customer' },
                    { data: 'total', name: 'total' },
                    { data: 'payment_method', name: 'payment_method' },
                    { data: 'status', name: 'status' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action' }
                ],
                "order": [[ 0, "desc" ]],
            });
        });

        $('body').on('click', '.detail', function () {
            var id = $(this).data('id');
            var url = "{{ route('pesanan.show', ['pesanan' => ':id']) }}".replace(':id', id);

            $.ajax({
                url: url,
                type: 'GET',
                success: function(response) {

                    // Set customer information
                    $('#akun-name').text(response.user.name);
                    $('#akun-email').text(response.alamat.email);
                    $('#penerima-name').text(response.alamat.first_name + ' ' + response.alamat.last_name);
                    $('#penerima-phone').text(response.alamat.phone);
                    $('#penerima-email').text(response.alamat.email);

                    // Set data-id for proses button
                    $('.proses').attr('data-id', response.id);

                    // Set button text based on status
                    if (response.status === 'pending' || response.status === 'Menunggu Pembayaran') {
                        $('#proses-pesanan').text('Proses Pembayaran');
                    } else if (response.status === 'processing' || response.status === 'Pesanan Diproses') {
                        $('#proses-pesanan').text('Kirim Pesanan');
                    } else if (response.status === 'shipping' || response.status === 'Dalam Pengiriman') {
                        $('#proses-pesanan').text('Selesaikan Pesanan');
                    } else if (response.status === 'success' || response.status === 'Pesanan Selesai') {
                        $('#proses-pesanan').hide();
                    } else {
                        $('#proses-pesanan').text('Proses Pesanan');
                    }

                    // Set order information
                    $('#order-id').text(response.midtrans.order_id);
                    $('#order-status').text(response.status);
                    $('#payment-method').text(response.payment_method);

                    // Set shipping address
                    var address = response.alamat.address_line1;
                    if (response.alamat.address_line2) {
                        address += ', ' + response.alamat.address_line2;
                    }
                    address += ', ' + response.alamat.state_country;
                    address += ', ' + response.alamat.postal_code;
                    $('#shipping-address').text(address);

                    // Set products
                    var productList = '';
                    response.items.forEach(function(item) {
                        productList += `
                            <tr>
                                <td>${item.product.nama_produk}</td>
                                <td>Rp. ${Number(item.price).toLocaleString('id-ID')}</td>
                                <td>${item.quantity}</td>
                                <td>Rp. ${Number(item.subtotal).toLocaleString('id-ID')}</td>
                            </tr>
                        `;
                    });
                    $('#product-list').html(productList);

                    // Set total
                    $('#order-total').text('Rp. ' + Number(response.total).toLocaleString('id-ID'));

                    // Update tracking status
                    updateTrackingStatus(response.status);
                    // console.log(response.status);

                    // Show modal
                    $('#detailModal').modal('show');
                },
                error: function(xhr) {
                    toastr.error('Terjadi kesalahan saat mengambil data pesanan.');
                }
            });
        });

        $('body').on('click', '.bayar', function () {
            var snapToken = $(this).data('id');
            snap.pay(snapToken, {
                onSuccess: function(result) {
                    toastr.success('Pembayaran berhasil!');
                    $('#datatable').DataTable().ajax.reload();
                },
                onPending: function(result) {
                    toastr.info('Pembayaran pending!');
                    $('#datatable').DataTable().ajax.reload();
                },
                onError: function(result) {
                    toastr.error('Pembayaran gagal!');
                },
                onClose: function() {
                    toastr.info('Anda menutup popup tanpa menyelesaikan pembayaran');
                }
            });
        });

        $('body').on('click', '.delete', function () {
            var id = $(this).data('id');
            var url = "{{ route('pesanan.destroy', ['pesanan' => ':id']) }}".replace(':id', id);
            Swal.fire({
                title: 'Anda yakin?',
                text: "Apakah Anda yakin ingin Membatalkan Pesanan ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, batalkan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {
                            _method: 'DELETE'
                        },
                        success: function (data) {
                            $('#datatable').DataTable().ajax.reload();
                            toastr.success('Pesanan berhasil dibatalkan!');
                        },
                        error: function (data) {
                            console.log('Error:', data);
                            toastr.error('Terjadi kesalahan saat membatalkan pesanan.');
                        }
                    });
                }
            });
        });

        function updateTrackingStatus(status) {
            // Reset all steps
            $('.step').removeClass('active').removeAttr('data-current');

            // Map status to step
            let step = 'pending';
            switch(status) {
                case 'pending':
                case 'Menunggu Pembayaran':
                    step = 'pending';
                    break;
                case 'processing':
                case 'Pesanan Diproses':
                    step = 'processing';
                    break;
                case 'shipping':
                case 'Dalam Pengiriman':
                    step = 'shipping';
                    break;
                case 'success':
                case 'Pesanan Selesai':
                    step = 'delivered';
                    break;
                case 'failed':
                case 'Pembayaran Gagal':
                    step = 'pending';
                    break;
                default:
                    step = 'pending';
            }

            // Activate steps up to current status
            const steps = ['pending', 'processing', 'shipping', 'delivered'];
            const currentStep = steps.indexOf(step);

            // If status is failed, only show pending step
            if (status === 'failed' || status === 'Pembayaran Gagal') {
                $('#step-pending').addClass('active').attr('data-current', 'true');
                return;
            }

            // Activate all steps up to current status
            steps.forEach((stepName, index) => {
                const stepElement = document.getElementById(`step-${stepName}`);
                if (index <= currentStep) {
                    stepElement.classList.add('active');
                    // Only mark the current step for animation
                    if (index === currentStep) {
                        stepElement.setAttribute('data-current', 'true');
                    }
                } else {
                    stepElement.classList.remove('active');
                }
            });
        }

        $('body').on('click', '.proses', function () {
            var id = $(this).data('id');
            var url = "{{ route('pesanan.proses', ['id' => ':id']) }}".replace(':id', id);

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if(response.success) {
                        toastr.success('Status pesanan berhasil diupdate');
                        $('#detailModal').modal('hide');
                        $('#pesanan-table').DataTable().ajax.reload();
                        updateStepProgress(response.status);
                    } else {
                        toastr.error('Gagal mengupdate status pesanan');
                    }
                },
                error: function(xhr) {
                    toastr.error('Terjadi kesalahan saat mengupdate status');
                }
            });
        });
    </script>
</x-app-layout>
