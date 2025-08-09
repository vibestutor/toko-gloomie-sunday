<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Matikan aturan keamanan untuk sementara
        Schema::disableForeignKeyConstraints();

        // 2. Kosongkan tabel-tabel (membersihkan data lama)
        Category::truncate();
        Product::truncate();
        User::truncate();
        Order::truncate();
        OrderItem::truncate();

        // 3. Nyalakan kembali aturan keamanan
        Schema::enableForeignKeyConstraints();

        // 4. Panggil seeder lain untuk mengisi data baru yang bersih
        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
        ]);
    }
}