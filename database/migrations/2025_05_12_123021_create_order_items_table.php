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
    Schema::create('order_items', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->bigInteger('order_id')->unsigned()->nullable();
        $table->bigInteger('product_id')->unsigned()->nullable();
        $table->integer('quantity');
        $table->decimal('price', 15, 2);
        $table->decimal('subtotal', 15, 2);
        $table->timestamps();

        $table->foreign('order_id')
            ->references('id')
            ->on('orders')
            ->onDelete('cascade');

        $table->foreign('product_id')
            ->references('id')
            ->on('produks')
            ->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');

    }
};
