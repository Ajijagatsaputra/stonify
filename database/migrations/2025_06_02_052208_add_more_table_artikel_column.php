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
        Schema::table('artikels', function (Blueprint $table) {
            $table->string('tags')->nullable()->after('judul');
            $table->string('status')->nullable()->after('tags');
            $table->string('kategori')->nullable()->after('status');
            $table->text('deskripsi_singkat')->nullable()->after('kategori');
            $table->string('slug')->nullable()->after('deskripsi_singkat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('artikels', function (Blueprint $table) {
            $table->dropColumn('tags');
            $table->dropColumn('status');
            $table->dropColumn('kategori');
            $table->dropColumn('deskripsi_singkat');
            $table->dropColumn('slug');
        });
    }
};
