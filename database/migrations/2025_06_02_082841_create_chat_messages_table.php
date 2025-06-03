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
        Schema::table('chats', function (Blueprint $table) {
            // Drop existing foreign keys
            $table->dropForeign(['sender_id']);
            $table->dropForeign(['receiver_id']);
            $table->dropForeign(['chat_room_id']);

            // Drop existing columns
            $table->dropColumn(['sender_id', 'receiver_id']);

            // Add new columns
            $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            $table->boolean('is_admin')->default(false)->after('message');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chats', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn(['user_id', 'is_admin']);

            // Restore original columns
            $table->foreignId('sender_id')->constrained()->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained()->onDelete('cascade');
        });
    }
};
