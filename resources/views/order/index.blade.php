
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
                            <div class="card-header">
                              <h3 class="card-title">Daftar Pesanan</h3>
                              
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                              <table class="table" id="datatable">
                                  <thead>
                                      <tr>
                                        <th>Nomor Pesanan</th>
                                        <th>Nama Produk</th>
                                        <th>Harga</th>
                                        <th>Jumlah Pesanan</th>
                                        <th>Status Pesanan</th>
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


<script type="text/javascript">
    $(document).ready(function() {
        $('#datatable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('orders.history') }}",
                "type": "GET"
            },
            "columns": [
                { data: 'id', name: 'id' },
                { data: 'nama', name: 'nama' },
                { data: 'alamat', name: 'alamat' },
                { data: 'total', name: 'total' },
                { data: 'status', name: 'status' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action' }
            ],
            "order": [[ 0, "desc" ]],
            "columnDefs": [
                { "targets": [5], "orderable": false }
            ]
        });
    });
</script>

</x-app-layout>
