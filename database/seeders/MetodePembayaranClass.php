<?php

namespace Database\Seeders;

use App\Models\MetodePembayaran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MetodePembayaranClass extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MetodePembayaran::insert([
            [
                'nama' => 'BCA',
                'kode' => 'bca_va',
                'logo' => 'https://www.bca.co.id/favicon.ico',
                'deskripsi' => 'Transfer via BCA',
                'status' => true,
            ],
            [
                'nama' => 'BNI',
                'kode' => 'bni_va',
                'logo' => 'https://www.bni.co.id/favicon.ico',
                'deskripsi' => 'Transfer via BNI',
                'status' => true,
            ],
            [
                'nama' => 'BRI',
                'kode' => 'bri_va',
                'logo' => 'https://www.bri.co.id/favicon.ico',
                'deskripsi' => 'Transfer via BRI',
                'status' => true,
            ],
            [
                'nama' => 'Mandiri',
                'kode' => 'mandiri_va',
                'logo' => 'https://www.bankmandiri.co.id/favicon.ico',
                'deskripsi' => 'Transfer via Mandiri',
                'status' => true,
            ],
            [
                'nama' => 'QRIS',
                'kode' => 'other_qris',
                'logo' => 'https://www.qris.id/favicon.ico',
                'deskripsi' => 'Pembayaran via QRIS',
                'status' => true,
            ],
            [
                'nama' => 'COD',
                'kode' => 'cod',
                'logo' => null,
                'deskripsi' => 'Pembayaran via COD',
                'status' => true,
            ]
        ]);
    }
}
