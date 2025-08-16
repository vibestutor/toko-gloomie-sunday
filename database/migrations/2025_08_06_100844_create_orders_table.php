<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // boleh nullable agar guest checkout jalan
            $table->foreignId('user_id')->nullable()
                  ->constrained('users')->nullOnDelete();

            // identitas penerima
            $table->string('name');
            $table->string('email');
            $table->string('phone', 40);
            $table->string('address', 600);

            // kode & status
            $table->string('code')->unique();              // ex: GK2A7Q3H9D
            $table->string('status')->default('pending');  // pending|paid|failed|expired

            // total selalu dalam base IDR (tanpa desimal)
            $table->unsignedBigInteger('total');
            $table->string('currency', 3)->default('IDR');

            // metode/penyedia pembayaran
            $table->string('payment_method')->nullable();  // xendit|bca_manual|...
            $table->string('payment_channel')->nullable(); // qris|bca_va|...

            // integrasi Xendit
            $table->string('external_id')->nullable()->index();  // ex: order_CODE
            $table->string('invoice_id')->nullable()->index();
            $table->string('invoice_url')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
