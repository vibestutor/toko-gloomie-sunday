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
    Schema::table('products', function (Blueprint $table) {
        // Menambahkan kolom baru setelah kolom 'image_url'
        $table->string('image_hover_url')->nullable()->after('image_url');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn('image_hover_url');
    });
}
};
