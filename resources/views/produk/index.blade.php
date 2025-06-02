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
                {{ __('Kelola Produk') }}
            </h2>
        </div>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h3 class="card-title mb-0">Daftar Produk</h3>
                                
                                <a href="{{ route('produk.create') }}" class="px-6 py-3 bg-indigo-600 rounded-full font-semibold hover:bg-indigo-700 transition duration-300 text-center">
                                  Tambah Produk
                                </a>
                              </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                              <table class="table" id="datatable">
                                  <thead>
                                      <tr>
                                        <th>#</th>
                                        <th>Foto</th>
                                        <th>Nama Produk</th>
                                        <th>Stok</th>
                                        <th>Harga</th>
                                        <th>Dibuat Pada</th>
                                        <th>Diperbarui Pada</th>
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

    <!-- SweetAlert2 Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

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
                    "url": "{{ route('produk.index') }}",
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
                    { data: 'foto_produk', name: 'foto_produk' },
                    { data: 'nama_produk', name: 'nama_produk' },
                    { data: 'stok', name: 'stok' },
                    { data: 'harga', name: 'harga' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'updated_at', name: 'updated_at' },
                    { data: 'action', name: 'action' }
                ],
                "order": [[ 0, "desc" ]],

            });
        });
    </script>

    <script>
        $('body').on('click', '.edit', function () {
            var id = $(this).data('id');
            var url = "{{ route('produk.edit', ['produk' => ':id']) }}".replace(':id', id);
            window.location.href = url;
        });
        $('body').on('click', '.delete', function () {
            var id = $(this).data('id');
            var url = "{{ route('produk.destroy', ['produk' => ':id']) }}".replace(':id', id);
            Swal.fire({
                title: 'Anda yakin?',
                text: "Apakah Anda yakin ingin menghapus data ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "DELETE",
                        url: url,
                        success: function (data) {
                            $('#datatable').DataTable().ajax.reload();
                            toastr.success('Data berhasil dihapus!');
                        },
                        error: function (data) {
                            console.log('Error:', data);
                            toastr.error('Terjadi kesalahan saat menghapus data.');
                        }
                    });
                }
            });
        });
    </script>
</x-app-layout>
