<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_id')->constrained('carts')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('qty');
            // snapshot untuk tampilan (opsional, tapi bikin UI stabil)
            $table->string('name_snapshot');
            $table->string('image_snapshot')->nullable();
            $table->unsignedBigInteger('price_snapshot'); // simpan dalam rupiah (integer)
            $table->timestamps();

            $table->unique(['cart_id','product_id','product_variant_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('cart_items');
    }
};
