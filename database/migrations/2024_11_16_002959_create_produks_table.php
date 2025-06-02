<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produks', function (Blueprint $table) {
            $table->id(); // ID produk
            $table->string('nama_produk'); // Nama produk
            $table->integer('stok'); // Stok produk
            $table->decimal('harga', 10, 2); // Harga produk (format desimal)
            $table->text('deskripsi')->nullable(); // Deskripsi produk (opsional)
            $table->string('foto_produk')->nullable(); // Nama file foto produk (opsional)
            $table->timestamps(); // Timestamps (created_at & updated_at)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
   
};
