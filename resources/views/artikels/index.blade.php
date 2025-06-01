<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Artikel') }}
            </h2>
            <a href="{{ route('artikels.create') }}" class="mt-4 sm:mt-0 px-6 py-3 bg-indigo-600 text-white rounded-full font-semibold hover:bg-indigo-700 transition duration-300 text-center">
                Tambah Artikel
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    @forelse ($artikels as $artikel)
                    <div class="rounded-lg p-4 mb-4 flex flex-col sm:flex-row items-start sm:items-center justify-between border-b">
                        <div class="flex-1 flex flex-col sm:flex-row items-start sm:items-center space-y-2 sm:space-y-0 sm:space-x-6">
                            @if($artikel->gambar)
                            <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="{{ $artikel->judul }}" class="w-24 h-24 object-cover rounded-lg">
                            @endif
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-700">Judul</p>
                                <p class="mt-1 text-lg font-semibold text-gray-900">{{ $artikel->judul }}</p>
                                <p class="text-sm mt-1 text-gray-600 line-clamp-2">{{ Str::limit($artikel->konten, 100) }}</p>
                            </div>
                        </div>

                        <div class="mt-4 sm:mt-0 flex space-x-2">
                            <a href="{{ route('artikels.edit', $artikel->id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-full font-semibold hover:bg-indigo-700 transition duration-300">
                                Edit
                            </a>
                            <form action="{{ route('artikels.destroy', $artikel->id) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-full font-semibold hover:bg-red-700 transition duration-300">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                        <p class="p-6 text-gray-500">Belum ada artikel yang ditambahkan.</p>
                    @endforelse
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
                    e.preventDefault();
                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Artikel ini akan dihapus dan tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>
