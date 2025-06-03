<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-center text-gray-800 leading-tight">
            {{ __('Kelola Artikel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </div>
                        <div class="ml-3">
                            {{ session('success') }}
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center space-x-2">

                </div>
                <a href="{{ route('artikels.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700  rounded-lg font-semibold hover:from-indigo-700 hover:to-indigo-800 transition duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    <i class="fas fa-plus mr-2"></i>Tambah Artikel
                </a>
            </div>

            <div class="bg-white shadow-xl rounded-lg overflow-hidden border border-gray-100">
                <div class="p-6">
                    @forelse ($artikels as $artikel)
                    <div class="rounded-lg p-4 mb-4 flex items-center justify-between border border-gray-200 hover:border-indigo-300 transition duration-300 bg-white hover:bg-gray-50 group">
                        <div class="flex items-center space-x-4 flex-1">
                            @if($artikel->gambar)
                            <div class="w-16 h-16 flex-shrink-0 relative group-hover:scale-105 transition-transform duration-300 me-5">
                                <img src="{{ asset('storage/' . $artikel->gambar) }}" alt="{{ $artikel->judul }}" class="w-full h-full object-cover rounded-lg shadow-sm">
                            </div>
                            @else
                            <div class="w-16 h-16 flex-shrink-0 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg me-5 flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                                <i class="fas fa-image text-gray-400 text-xl"></i>
                            </div>
                            @endif

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-base font-semibold text-gray-900 truncate group-hover:text-indigo-600 transition-colors duration-300">{{ $artikel->judul }}</h3>
                                    <span class="text-xs text-gray-500 ml-2 flex items-center">
                                        <i class="far fa-clock mr-1"></i>
                                        {{ $artikel->created_at->format('d M Y') }}
                                    </span>
                                </div>
                                <p class="mt-1 text-sm text-gray-600 line-clamp-1">{{ Str::limit($artikel->konten, 100) }}</p>
                                <p class="mt-1 text-sm text-gray-600 line-clamp-1">Kategori : {{ $artikel->kategori }}</p>
                                <p class=" text-sm text-gray-600">Tags : {{ $artikel->tags }}</p>
                                <div class="mt-1">
                                    @if($artikel->status == 'published')
                                        <span class="badge bg-success">
                                            Published
                                        </span>
                                    @elseif($artikel->status == 'draft')
                                        <span class="badge bg-warning">
                                            Draft
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            {{ $artikel->status }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center space-x-2 ml-4">
                            <a href="{{ route('artikels.edit', $artikel->id) }}" class="p-2 text-yellow-600 hover:text-yellow-700 hover:bg-yellow-50 rounded-lg transition duration-300 transform hover:scale-110">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="javascript:void(0)" data-toggle="tooltip" data-id="{{ $artikel->id }}" data-original-title="Delete" class="delete p-2 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition duration-300 transform hover:scale-110 ">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </div>
                    </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-full w-24 h-24 flex items-center justify-center mx-auto mb-4 shadow-inner">
                                <i class="fas fa-newspaper text-gray-400 text-4xl"></i>
                            </div>
                            <p class="text-gray-500 text-lg mb-4">Belum ada artikel yang ditambahkan.</p>
                            <a href="{{ route('artikels.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-lg font-semibold hover:from-indigo-700 hover:to-indigo-800 transition duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                <i class="fas fa-plus mr-2"></i>
                                Tambah Artikel Pertama
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- SweetAlert2 Script -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            $('body').on('click', '.delete', function () {
            var id = $(this).data('id');
            var url = "{{ route('artikels.destroy', ['artikel' => ':id']) }}".replace(':id', id);
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
                            toastr.success('Data berhasil dihapus!');
                            setTimeout(function() {
                                window.location.reload();
                            }, 2000);
                        },
                        error: function (data) {
                            console.log('Error:', data);
                            toastr.error('Terjadi kesalahan saat menghapus data.');
                        }
                    });
                }
            });
        });
    });
    </script>
</x-app-layout>
