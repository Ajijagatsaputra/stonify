<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Produk') }}
            </h2>
            <a href="{{ route('produk.index') }}" class="px-6 py-3 bg-gray-500 text-white rounded-full font-semibold hover:bg-gray-600 transition duration-300 text-center">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden p-6">
                <div class="mb-4">
                    <strong class="text-sm font-medium text-gray-700">Nama Produk:</strong>
                    <p class="mt-1 text-gray-900">{{ $produk->nama_produk }}</p>
                </div>

                <div class="mb-4">
                    <strong class="text-sm font-medium text-gray-700">Stok:</strong>
                    <p class="mt-1 text-gray-900">{{ $produk->stok }}</p>
                </div>

                <div class="mb-4">
                    <strong class="text-sm font-medium text-gray-700">Harga:</strong>
                    <p class="mt-1 text-gray-900">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                </div>

                <div class="mb-4">
                    <strong class="text-sm font-medium text-gray-700">Deskripsi:</strong>
                    <p class="mt-1 text-gray-900">{{ $produk->deskripsi }}</p>
                </div>

                <div class="mb-4">
                    <strong class="text-sm font-medium text-gray-700">Foto Produk:</strong>
                    <div class="mt-1">
                        <img src="{{ asset('storage/'.$produk->foto_produk) }}" alt="Foto Produk" class="w-48 h-48 object-cover">
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('produk.edit', $produk->id) }}" class="px-6 py-3 bg-indigo-600 text-white rounded-full font-semibold hover:bg-indigo-700 transition duration-300">
                        Edit Produk
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
