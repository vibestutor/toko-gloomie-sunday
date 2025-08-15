<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('products', function (Blueprint $table) {
            // Pastikan kolom price bertipe integer (rupiah)
            // $table->unsignedBigInteger('price')->change(); // aktifkan kalau tipe lama bukan integer

            // Harga promo/flash sale (opsional)
            $table->unsignedBigInteger('sale_price')->nullable()->after('price');

            // Status publish (opsional)
            $table->boolean('is_active')->default(true)->after('image_hover_url');
        });
    }
    public function down(): void {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['sale_price','is_active']);
        });
    }
};
