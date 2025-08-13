<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('cart_token', 64)->nullable()->unique(); // untuk guest
            $table->timestamp('expires_at')->nullable();            // opsional
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('carts');
    }
};
