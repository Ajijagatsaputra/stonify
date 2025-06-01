<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Produk') }}
            </h2>
            <a href="{{ route('produk.index') }}" class="px-6 py-3 bg-gray-500 text-white rounded-full font-semibold hover:bg-gray-600 transition duration-300 text-center">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden p-6">
            <form action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <!-- Nama Produk -->
    <div class="mb-4">
        <label for="nama_produk" class="block text-sm font-medium text-gray-700">Nama Produk</label>
        <input type="text" name="nama_produk" id="nama_produk" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Masukkan nama produk" required>
    </div>

    <!-- Stok -->
    <div class="mb-4">
        <label for="stok" class="block text-sm font-medium text-gray-700">Stok</label>
        <input type="number" name="stok" id="stok" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Masukkan jumlah stok" required>
    </div>

    <!-- Harga -->
    <div class="mb-4">
        <label for="harga" class="block text-sm font-medium text-gray-700">Harga</label>
        <input type="number" name="harga" id="harga" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Masukkan harga produk" required>
    </div>

    <!-- Deskripsi -->
    <div class="mb-4">
        <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
        <textarea name="deskripsi" id="deskripsi" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Masukkan deskripsi produk"></textarea>
    </div>

    <!-- Foto -->
    <div class="mb-4">
        <label for="foto_produk" class="block text-sm font-medium text-gray-700">Foto Produk</label>
        <input type="file" name="foto_produk" id="foto_produk" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:bg-indigo-600 file:text-white hover:file:bg-indigo-700">
    </div>

    <!-- Tombol Simpan -->
    <div class="mt-6 flex justify-end">
        <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-full font-semibold hover:bg-indigo-700 transition duration-300">
            Simpan
        </button>
    </div>
</form>
@if (session('success'))
    <div class="bg-green-500 text-white p-4 rounded-lg mb-4 text-center">
        {{ session('success') }}
    </div>
@endif

            </div>
        </div>
    </div>
</x-app-layout>
