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
                        <!-- <tbody>
                            @foreach ($pesanan as $item)
                                <tr class="border-b">
                                    <td class="px-4 py-3">{{ $item->order_number }}</td>
                                    <td class="px-4 py-3">{{ $item->produk->name }}</td>
                                    <td class="px-4 py-3">Rp{{ number_format($item->produk->price, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">{{ $item->quantity }}</td>
                                    <td class="px-4 py-3">{{ ucfirst($item->status) }}</td>
                                    <td class="px-4 py-3 flex space-x-2">
                                        <a href="{{ route('pesanan.edit', $item->id) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-full font-semibold hover:bg-indigo-700 transition duration-300">
                                            Edit
                                        </a>
                                        <form action="{{ route('pesanan.destroy', $item->id) }}" method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-full font-semibold hover:bg-red-700 transition duration-300">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody> -->
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
