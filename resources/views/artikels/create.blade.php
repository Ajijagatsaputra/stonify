<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Artikel') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <form action="{{ route('artikels.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Judul Artikel -->
                    <div class="mb-6">
                        <label for="judul" class="block text-sm font-medium text-gray-700">Judul Artikel*</label>
                        <input type="text" name="judul" id="judul"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Masukkan judul artikel" required>
                        <p class="mt-1 text-sm text-gray-500">Judul yang menarik akan meningkatkan minat pembaca</p>
                    </div>

                    <!-- Kategori -->
                    <div class="mb-6">
                        <label for="kategori" class="block text-sm font-medium text-gray-700">Kategori*</label>
                        <select name="kategori" id="kategori"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500" required>
                            <option value="">Pilih Kategori</option>
                            <option value="tips">Tips & Trik</option>
                            <option value="inspirasi">Inspirasi</option>
                            <option value="trend">Trend</option>
                            <option value="news">Berita</option>
                        </select>
                    </div>

                    <!-- Deskripsi Singkat -->
                    <div class="mb-6">
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi Singkat*</label>
                        <textarea name="deskripsi_singkat" id="deskripsi_singkat" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Masukkan deskripsi singkat artikel (maksimal 150 karakter)" required></textarea>
                        <p class="mt-1 text-sm text-gray-500">Deskripsi singkat yang akan muncul di halaman artikel</p>
                    </div>

                    <!-- Konten Artikel -->
                    <div class="mb-6">
                        <label for="konten" class="block text-sm font-medium text-gray-700">Konten Artikel*</label>
                        <textarea name="konten" id="konten" rows="10"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Tulis konten artikel Anda di sini..." required></textarea>
                        <p class="mt-1 text-sm text-gray-500">Gunakan format yang jelas dan mudah dibaca</p>
                    </div>

                    <!-- Gambar Utama -->
                    <div class="mb-6">
                        <label for="gambar" class="block text-sm font-medium text-gray-700">Gambar Utama*</label>
                        <input type="file" name="gambar" id="gambar"
                            class="mt-1 block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-indigo-50 file:text-indigo-700
                            hover:file:bg-indigo-100"
                            accept="image/*" required>
                        <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, atau JPEG. Ukuran maksimal: 2MB</p>
                    </div>

                    <!-- Tags -->
                    <div class="mb-6">
                        <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
                        <input type="text" name="tags" id="tags"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Contoh: batu alam, dekorasi, tips (pisahkan dengan koma)">
                        <p class="mt-1 text-sm text-gray-500">Tambahkan tags untuk memudahkan pencarian artikel</p>
                    </div>

                    <!-- Status Publikasi -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700">Status Publikasi</label>
                        <div class="mt-2 space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="status" value="published" class="form-radio text-indigo-600" checked>
                                <span class="ml-2">Publikasikan Sekarang</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="status" value="draft" class="form-radio text-indigo-600">
                                <span class="ml-2">Simpan sebagai Draft</span>
                            </label>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('artikels.index') }}"
                            class="px-4 py-2 bg-gray-200 rounded-md text-gray-700 hover:bg-gray-300 transition duration-150">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 rounded-md hover:bg-indigo-700 transition duration-150">
                            Simpan Artikel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Preview image before upload
        document.getElementById('gambar').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) { // 2MB
                    alert('Ukuran file terlalu besar. Maksimal 2MB');
                    this.value = '';
                }
            }
        });

        // Character counter for deskripsi
        document.getElementById('deskripsi_singkat').addEventListener('input', function(e) {
            const maxLength = 150;
            const currentLength = e.target.value.length;
            const counter = e.target.parentElement.querySelector('.text-gray-500');
            if (currentLength > maxLength) {
                e.target.value = e.target.value.substring(0, maxLength);
            }
            counter.textContent = `${currentLength}/${maxLength} karakter`;
        });
    </script>
    @endpush
</x-app-layout>