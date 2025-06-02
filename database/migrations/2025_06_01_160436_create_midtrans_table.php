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
        Schema::create('midtrans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('transaction_id')->unsigned();
            $table->text('snap_token')->nullable();
            $table->string('order_id', 100);
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_type')->nullable();
            $table->string('fraud_status')->nullable();
            $table->enum('status', ['pending', 'settlement', 'expire', 'cancel'])->default('pending');

            $table->foreign('transaction_id')
                ->references('id')
                ->on('orders')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('midtrans');
    }
};
