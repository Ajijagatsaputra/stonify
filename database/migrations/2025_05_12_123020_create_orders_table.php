<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('nama_depan');
        $table->string('nama_belakang');
        $table->string('alamat');
        $table->string('state_country');
        $table->string('postal_zip');
        $table->string('email');
        $table->string('phone');
        $table->text('notes')->nullable();
        $table->string('payment_method');
        $table->integer('total');
        $table->string('va_number')->nullable();
        $table->enum('status', ['pending', 'confirmed', 'shipped'])->default('pending');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
