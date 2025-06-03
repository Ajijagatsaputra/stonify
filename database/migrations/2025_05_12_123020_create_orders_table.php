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
        $table->bigIncrements('id');
        $table->bigInteger('user_id')->unsigned()->index();
        $table->decimal('total', 15, 2);
        $table->enum('status', ['pending', 'paid', 'failed', 'cancelled', 'shipping', 'success'])->default('pending');
        $table->string('payment_method')->nullable();
        $table->text('order_notes')->nullable();
        $table->timestamps();

        $table->foreign('user_id')->references('id')->on('users');
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
