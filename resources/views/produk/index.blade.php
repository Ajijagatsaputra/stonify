<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Produk') }}
            </h2>
            <a href="{{ route('produk.create') }}" class="mt-4 sm:mt-0 px-6 py-3 bg-indigo-600 text-white rounded-full font-semibold hover:bg-indigo-700 transition duration-300 text-center">
                Tambah Produk
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <!-- Scrollable container on small screens -->
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100 text-left">
                                <th class="px-4 py-2 font-medium text-gray-700">Foto</th>
                                <th class="px-4 py-2 font-medium text-gray-700">Nama Produk</th>
                                <th class="px-4 py-2 font-medium text-gray-700">Stok</th>
                                <th class="px-4 py-2 font-medium text-gray-700">Harga</th>
                                <th class="px-4 py-2 font-medium text-gray-700">Deskripsi</th>
                                <th class="px-4 py-2 font-medium text-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($produk as $item)
                                <tr class="border-b">
                                    <td class="px-4 py-3">
                                        @if ($item->foto_produk)
                                            <img src="{{ asset('storage/' . $item->foto_produk) }}" alt="{{ $item->nama_produk }}" class="w-16 h-16 rounded object-cover">
                                        @else
                                            <span class="text-gray-500">Tidak ada foto</span>
                                        @endif
                                    </td>

                                    <td class="px-4 py-3">
                                        <p class="font-semibold text-gray-800">{{ $item->nama_produk }}</p>
                                    </td>

                                    <td class="px-4 py-3">
                                        <p class="text-gray-800">{{ $item->stok }}</p>
                                    </td>

                                    <td class="px-4 py-3">
                                        <p class="text-gray-800">Rp{{ number_format($item->harga, 0, ',', '.') }}</p>
                                    </td>

                                    <td class="px-4 py-3">
                                        <p class="text-gray-600">{{ Str::limit($item->deskripsi, 50) }}</p>
                                    </td>

                                    <td class="px-4 py-3 flex space-x-2">
                                        <a href="{{ route('produk.edit', $item->id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-full font-semibold hover:bg-indigo-700 transition duration-300">
                                            Edit
                                        </a>
                                        <form action="{{ route('produk.destroy', $item->id) }}" method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-full font-semibold hover:bg-red-700 transition duration-300">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center px-4 py-4 text-gray-500">
                                        Belum ada produk yang ditambahkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault(); // Prevent the default form submission

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data ini akan dihapus dan tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // Submit the form if confirmed
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>
