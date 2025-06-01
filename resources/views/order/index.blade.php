<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Kelola Pesanan') }}
            </h2>

        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full table-auto">
                        <thead>
                            <tr class="bg-gray-100 text-left">
                                <th class="px-4 py-2 font-medium text-gray-700">Nomor Pesanan</th>
                                <th class="px-4 py-2 font-medium text-gray-700">Nama Produk</th>
                                <th class="px-4 py-2 font-medium text-gray-700">Harga</th>
                                <th class="px-4 py-2 font-medium text-gray-700">Jumlah Pesanan</th>
                                <th class="px-4 py-2 font-medium text-gray-700">Status Pesanan</th>
                                <th class="px-4 py-2 font-medium text-gray-700">Aksi</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
