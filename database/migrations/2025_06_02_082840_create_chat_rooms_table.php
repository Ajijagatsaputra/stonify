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
        Schema::table('chat_rooms', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained('users')->onDelete('cascade');
            $table->foreignId('admin_id')->after('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name')->after('admin_id');
            $table->string('slug')->unique()->after('name');
            $table->text('description')->nullable()->after('slug');
            $table->boolean('is_active')->default(true)->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_rooms', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn(['name', 'slug', 'description', 'is_active']);

            // Restore original columns
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('admin_id')->constrained()->onDelete('cascade');
        });
    }
};
