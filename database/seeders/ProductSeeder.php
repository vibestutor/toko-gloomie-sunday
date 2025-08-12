<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\ProductVariant;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {

            // helper untuk seed varian tanpa duplikat
            $seedVariants = function (Product $product, array $items) {
                foreach ($items as $v) {
                    ProductVariant::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'color'      => strtoupper($v['color']),
                        ],
                        [
                            'image_url'       => $v['image_url'],
                            'image_hover_url' => $v['image_hover_url'] ?? $v['image_url'],
                            'price'           => (int) $product->price,
                            'stock'           => $v['stock'] ?? 25,
                        ]
                    );
                }
            };

            // ========== P1: BOXY HEAVYWEIGHT ==========
            $p1 = Product::firstOrCreate(
                ['slug' => 'boxy-heavyweight'],
                [
                    'category_id'    => 1,
                    'name'           => 'GLOOMIE SUNDAY - HOODIE BOXY HEAVYWEIGHT',
                    'price'          => 184000,
                    'image_url'      => 'img/produk/BOXY HITAM 330.jpg',
                    'image_hover_url'=> 'img/produk/BOXY HITAM 330.jpg',
                    'description'    => 'Gloomie Sunday Original Indonesian Apparel. Fitting: Boxy Fit (Unisex). Material: Cotton Fleece (80% Cotton, 20% Polyester).',
                    'stock'          => 50,
                ]
            );

            $seedVariants($p1, [
                ['color'=>'BLACK',        'image_url'=>'img/produk/BOXY HITAM 330.jpg'],
                ['color'=>'BROKEN WHITE', 'image_url'=>'img/produk/KAOS BOXY.jpg', 'image_hover_url'=>'img/produk/KAOS BOXY B.jpg'],
                ['color'=>'DARK GREY',    'image_url'=>'img/produk/BOXY HITAM 330.jpg'],
                ['color'=>'NAVY',         'image_url'=>'img/produk/KAOS BOXY.jpg', 'image_hover_url'=>'img/produk/KAOS BOXY B.jpg'],
                ['color'=>'MISTY GREY',   'image_url'=>'img/produk/BOXY HITAM 330.jpg'],
                ['color'=>'DARK BROWN',   'image_url'=>'img/produk/KAOS BOXY.jpg', 'image_hover_url'=>'img/produk/KAOS BOXY B.jpg'],
                ['color'=>'ROYAL BLUE',   'image_url'=>'img/produk/KAOS BOXY.jpg', 'image_hover_url'=>'img/produk/KAOS BOXY B.jpg'],
            ]);

            // ========== P2: BOXY STANDARD ==========
            $p2 = Product::firstOrCreate(
                ['slug' => 'boxy-standard'],
                [
                    'category_id'    => 2,
                    'name'           => 'GLOOMIE SUNDAY - HOODIE BOXY STANDARD',
                    'price'          => 139000,
                    'image_url'      => 'img/produk/BOXY HITAM 330.jpg',
                    'image_hover_url'=> 'img/produk/BOXY HITAM 330.jpg',
                    'description'    => 'Sweatpants nyaman dengan desain modern untuk tampilan kasual.',
                    'stock'          => 50,
                ]
            );

            $seedVariants($p2, [
                ['color'=>'BLACK',      'image_url'=>'img/produk/BOXY HITAM 330.jpg'],
                ['color'=>'WHITE',      'image_url'=>'img/produk/KAOS BOXY.jpg', 'image_hover_url'=>'img/produk/KAOS BOXY B.jpg'],
                ['color'=>'DARK GREY',  'image_url'=>'img/produk/BOXY HITAM 330.jpg'],
                ['color'=>'NAVY',       'image_url'=>'img/produk/KAOS BOXY.jpg', 'image_hover_url'=>'img/produk/KAOS BOXY B.jpg'],
                ['color'=>'MISTY GREY', 'image_url'=>'img/produk/BOXY HITAM 330.jpg'],
                ['color'=>'DARK BROWN', 'image_url'=>'img/produk/BOXY HITAM 330.jpg'],
            ]);

            // ========== P11: HOODIE WITHOUT POCKET ==========
            $p11 = Product::firstOrCreate(
                ['slug' => 'hoodie-without-pocket'],
                [
                    'category_id'    => 11,
                    'name'           => 'GLOOMIE SUNDAY - HOODIE BOXY WITHOUT POCKET',
                    'price'          => 184000,
                    'image_url'      => 'img/produk/JERSEY.jpg',
                    'image_hover_url'=> 'img/produk/BOXY HITAM 330.jpg',
                    'description'    => 'Jersey olahraga dengan bahan berkualitas tinggi, nyaman untuk aktivitas sehari-hari.',
                    'stock'          => 50,
                ]
            );

            $seedVariants($p11, [
                ['color'=>'BLACK',        'image_url'=>'img/produk/BOXY HITAM 330.jpg'],
                ['color'=>'BROKEN WHITE', 'image_url'=>'img/produk/KAOS BOXY.jpg', 'image_hover_url'=>'img/produk/KAOS BOXY B.jpg'],
                ['color'=>'DARK GREY',    'image_url'=>'img/produk/BOXY HITAM 330.jpg'],
            ]);

            // ========== P5: SWEATPANTS BAGGY ==========
            $p5 = Product::firstOrCreate(
                ['slug' => 'sweatpants'],
                [
                    'category_id'    => 5,
                    'name'           => 'GLOOMIE SUNDAY - SWEATPANTS BAGGY',
                    'price'          => 178900,
                    'image_url'      => 'img/produk/SP MISTY.jpg',
                    'image_hover_url'=> 'img/produk/SP MISTY B.jpg',
                    'description'    => 'Gloomie Sunday Original Indonesian Apparel. Fitting: Boxy Fit (Unisex). Material: Cotton Fleece (80% Cotton, 20% Polyester).',
                    'stock'          => 50,
                ]
            );

            $seedVariants($p5, [
                ['color'=>'BLACK',      'image_url'=>'img/produk/BOXY HITAM 330.jpg'],
                ['color'=>'MISTY GREY', 'image_url'=>'img/produk/KAOS BOXY.jpg', 'image_hover_url'=>'img/produk/KAOS BOXY B.jpg'],
                ['color'=>'DARK GREY',  'image_url'=>'img/produk/BOXY HITAM 330.jpg'],
                ['color'=>'DARK BROWN', 'image_url'=>'img/produk/BOXY HITAM 330.jpg'],
            ]);

            // ========== JERSEY (tanpa varian) ==========
            Product::firstOrCreate(
                ['slug' => 'jersey'],
                [
                    'category_id'    => 10,
                    'name'           => 'GLOOMIE SUNDAY - SKOOLYARD JERSEY',
                    'price'          => 129000,
                    'image_url'      => 'img/produk/JERSEY.jpg',
                    'image_hover_url'=> 'img/produk/JERSEY B.jpg',
                    'description'    => 'Hoodie lengan pendek yang nyaman dengan desain modern untuk tampilan kasual.',
                    'stock'          => 50,
                ]
            );

            // ========== TSHIRT BOXY PRINTING (tanpa varian) ==========
            Product::firstOrCreate(
                ['slug' => 't-shirt-boxy-printing'],
                [
                    'category_id'    => 13,
                    'name'           => 'GLOOMIE SUNDAY - TSHIRT BOXY FAREWELL',
                    'price'          => 79000,
                    'image_url'      => 'img/produk/FAREWELL.jpg',
                    'image_hover_url'=> 'img/produk/FAREWELL B.jpg',
                    'description'    => 'T-Shirt Boxy Printing - FAREWELL',
                    'stock'          => 50,
                ]
            );

            // ========== P7: BOXY ZIPPER ==========
            $p7 = Product::firstOrCreate(
                ['slug' => 'boxy-zipper'],
                [
                    'category_id'    => 7,
                    'name'           => 'GLOOMIE SUNDAY - HOODIE BOXY ZIPPER',
                    'price'          => 239000,
                    'image_url'      => 'img/produk/ZIPPER.jpg',
                    'image_hover_url'=> 'img/produk/ZIPPER B.jpg',
                    'description'    => 'Kupluk beanie untuk melengkapi gayamu.',
                    'stock'          => 100,
                ]
            );

            $seedVariants($p7, [
                ['color'=>'BLACK',      'image_url'=>'img/produk/BOXY HITAM 330.jpg'],
                ['color'=>'MISTY GREY', 'image_url'=>'img/produk/KAOS BOXY.jpg', 'image_hover_url'=>'img/produk/KAOS BOXY B.jpg'],
                ['color'=>'DARK GREY',  'image_url'=>'img/produk/BOXY HITAM 330.jpg'],
                ['color'=>'DARK BROWN', 'image_url'=>'img/produk/BOXY HITAM 330.jpg'],
                ['color'=>'ROYAL BLUE', 'image_url'=>'img/produk/BOXY HITAM 330.jpg'],
            ]);

            // ========== BREAKER SWEATPANTS (tanpa varian) ==========
            Product::firstOrCreate(
                ['slug' => 'breaker-sweatpants'],
                [
                    'category_id'    => 9,
                    'name'           => 'GLOOMIE SUNDAY - BREAKER SWEATPANTS',
                    'price'          => 199000,
                    'image_url'      => 'img/produk/SP BREAKER.jpg',
                    'image_hover_url'=> 'img/produk/SP BREAKER.jpg',
                    'description'    => 'Hoodie dengan ritsleting dan potongan boxy yang nyaman.',
                    'stock'          => 50,
                ]
            );

            // ========== BREAKER CREWNECK (tanpa varian) ==========
            Product::firstOrCreate(
                ['slug' => 'breaker-crewneck'],
                [
                    'category_id'    => 9,
                    'name'           => 'GLOOMIE SUNDAY - BREAKER CREWNECK',
                    'price'          => 199000,
                    'image_url'      => 'img/produk/CREWNECK BREAKER.jpg',
                    'image_hover_url'=> 'img/produk/CREWNECK BREAKER DETAIL.jpg',
                    'description'    => 'Hoodie dengan ritsleting dan potongan boxy yang nyaman.',
                    'stock'          => 50,
                ]
            );

            // ========== HOODIE BOXY PRINTING — PARTY TO NIGHT ==========
            $p12a = Product::firstOrCreate(
                ['slug' => 'hoodie-boxy-party-to-night'],
                [
                    'category_id'    => 12,
                    'name'           => 'GLOOMIE SUNDAY - HOODIE BOXY PARTY TO NIGHT',
                    'price'          => 229000,
                    'image_url'      => 'img/produk/BOXY HITAM 330.jpg',
                    'image_hover_url'=> 'img/produk/BOXY HITAM 330.jpg',
                    'description'    => 'Gloomie Sunday Original Indonesian Apparel. Fitting: Boxy Fit (Unisex). Material: Cotton Fleece (80% Cotton, 20% Polyester).',
                    'stock'          => 50,
                ]
            );

            $seedVariants($p12a, [
                ['color'=>'BLACK', 'image_url'=>'img/produk/BOXY HITAM 330.jpg'],
                ['color'=>'NAVY',  'image_url'=>'img/produk/KAOS BOXY.jpg', 'image_hover_url'=>'img/produk/KAOS BOXY B.jpg'],
            ]);

            // ========== HOODIE BOXY PRINTING — YOUR EVERYTHING ==========
            $p12b = Product::firstOrCreate(
                ['slug' => 'hoodie-boxy-your-everything'],
                [
                    'category_id'    => 12,
                    'name'           => 'GLOOMIE SUNDAY - HOODIE BOXY YOUR EVERYTHING',
                    'price'          => 204000,
                    'image_url'      => 'img/produk/BOXY HITAM 330.jpg',
                    'image_hover_url'=> 'img/produk/BOXY HITAM 330.jpg',
                    'description'    => 'Gloomie Sunday Original Indonesian Apparel. Fitting: Boxy Fit (Unisex). Material: Cotton Fleece (80% Cotton, 20% Polyester).',
                    'stock'          => 50,
                ]
            );

            $seedVariants($p12b, [
                ['color'=>'BROKEN WHITE', 'image_url'=>'img/produk/BOXY HITAM 330.jpg'],
                ['color'=>'BLACK',        'image_url'=>'img/produk/KAOS BOXY.jpg', 'image_hover_url'=>'img/produk/KAOS BOXY B.jpg'],
            ]);

            // ========== P4 (NOTE: slug & name agak mismatch di data asli)
            // Kalau ini memang TSHIRT BOXY, gue biarin slug 't-shirt-boxy'
            // supaya unik dan nggak bentrok.
            $p4 = Product::firstOrCreate(
                ['slug' => 't-shirt-boxy'],
                [
                    'category_id'    => 1,
                    'name'           => 'GLOOMIE SUNDAY - HOODIE BOXY HEAVYWEIGHT',
                    'price'          => 68000,
                    'image_url'      => 'img/produk/BOXY HITAM 330.jpg',
                    'image_hover_url'=> 'img/produk/BOXY HITAM 330.jpg',
                    'description'    => 'Gloomie Sunday Original Indonesian Apparel. Fitting: Boxy Fit (Unisex). Material: Cotton Fleece (80% Cotton, 20% Polyester).',
                    'stock'          => 50,
                ]
            );

            // (Tidak menambah varian ke $p4, karena blok aslinya duplikat p1)
        });
    }
}
