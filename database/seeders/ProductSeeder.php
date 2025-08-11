<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $p1 = Product::create([
            'category_id' => 1, // boxy heavyweight
            'name' => 'GLOOMIE SUNDAY - HOODIE BOXY HEAVYWEIGHT',
            'slug' => 'boxy-heavyweight',
            'price' => 184000,
            'image_url' => 'img/produk/BOXY HITAM 330.jpg',
            'image_hover_url' => 'img/produk/BOXY HITAM 330.jpg',
            'description' => 'Gloomie Sunday Original Indonesian Apparel. Fitting: Boxy Fit (Unisex). Material: Cotton Fleece (80% Cotton, 20% Polyester).',
            'stock' => 50
        ]);

        ProductVariant::insert([
            [
                'product_id' => $p1->id,
                'color' => 'BLACK',
                'image_url' => 'img/produk/BOXY HITAM 330.jpg',
                'image_hover_url' => 'img/produk/BOXY HITAM 330.jpg',
                'price' => 184000,
                'stock' => 25,
            ],
            [
                'product_id' => $p1->id,
                'color' => 'BROKEN WHITE',
                'image_url' => 'img/produk/KAOS BOXY.jpg',
                'image_hover_url' => 'img/produk/KAOS BOXY B.jpg',
                'price' => 184000,
                'stock' => 25,
            ],
            [
                'product_id' => $p1->id,
                'color' => 'DARK GREY',
                'image_url' => 'img/produk/BOXY HITAM 330.jpg',
                'image_hover_url' => 'img/produk/BOXY HITAM 330.jpg',
                'price' => 184000,
                'stock' => 25,
            ],
            [
                'product_id' => $p1->id,
                'color' => 'NAVY',
                'image_url' => 'img/produk/KAOS BOXY.jpg',
                'image_hover_url' => 'img/produk/KAOS BOXY B.jpg',
                'price' => 184000,
                'stock' => 25,
            ],
            [
                'product_id' => $p1->id,
                'color' => 'MISTY GREY',
                'image_url' => 'img/produk/BOXY HITAM 330.jpg',
                'image_hover_url' => 'img/produk/BOXY HITAM 330.jpg',
                'price' => 184000,
                'stock' => 25,
            ],
            [
                'product_id' => $p1->id,
                'color' => 'DARK BROWN',
                'image_url' => 'img/produk/KAOS BOXY.jpg',
                'image_hover_url' => 'img/produk/KAOS BOXY B.jpg',
                'price' => 184000,
                'stock' => 25,
            ],
            [
                'product_id' => $p1->id,
                'color' => 'ROYAL BLUE',
                'image_url' => 'img/produk/KAOS BOXY.jpg',
                'image_hover_url' => 'img/produk/KAOS BOXY B.jpg',
                'price' => 184000,
                'stock' => 25,
            ],
        ]);

        $p2 = Product::create([
            'category_id' => 2, // boxy standard
            'name' => 'GLOOMIE SUNDAY - HOODIE BOXY STANDARD ',
            'slug' => 'boxy-standard',
            'price' => 139000,
            'image_url' => 'img/produk/BOXY HITAM 330.jpg',
            'image_hover_url' => 'img/produk/BOXY HITAM 330.jpg',
            'description' => 'Sweatpants nyaman dengan desain modern untuk tampilan kasual.',
            'stock' => 50
        ]);

        ProductVariant::insert([
            [
                'product_id' => $p2->id,
                'color' => 'BLACK',
                'image_url' => 'img/produk/BOXY HITAM 330.jpg',
                'image_hover_url' => 'img/produk/BOXY HITAM 330.jpg',
                'price' => 184000,
                'stock' => 25,
            ],
            [
                'product_id' => $p2->id,
                'color' => 'WHITE',
                'image_url' => 'img/produk/KAOS BOXY.jpg',
                'image_hover_url' => 'img/produk/KAOS BOXY B.jpg',
                'price' => 184000,
                'stock' => 25,
            ],
            [
                'product_id' => $p2->id,
                'color' => 'DARK GREY',
                'image_url' => 'img/produk/BOXY HITAM 330.jpg',
                'image_hover_url' => 'img/produk/BOXY HITAM 330.jpg',
                'price' => 184000,
                'stock' => 25,
            ],
            [
                'product_id' => $p2->id,
                'color' => 'NAVY',
                'image_url' => 'img/produk/KAOS BOXY.jpg',
                'image_hover_url' => 'img/produk/KAOS BOXY B.jpg',
                'price' => 184000,
                'stock' => 25,
            ],
            [
                'product_id' => $p2->id,
                'color' => 'MISTY GREY',
                'image_url' => 'img/produk/BOXY HITAM 330.jpg',
                'image_hover_url' => 'img/produk/BOXY HITAM 330.jpg',
                'price' => 184000,
                'stock' => 25,
            ],
            [
                'product_id' => $p2->id,
                'color' => 'DARK BROWN',
                'image_url' => 'img/produk/KAOS BOXY.jpg',
                'image_hover_url' => 'img/produk/KAOS BOXY B.jpg',
                'price' => 184000,
                'stock' => 25,
            ],
        ]);

        $p11 = Product::create([
            'category_id' => 11, // hoodie tanpa saku
            'name' => 'GLOOMIE SUNDAY - HOODIE BOXY WITHOUT POCKET',
            'slug' => 'hoodie-without-pocket',
            'price' => 184000,
            'image_url' => 'img/produk/JERSEY.jpg',
            'image_hover_url' => 'img/produk/BOXY HITAM 330.jpg',
            'description' => 'Jersey olahraga dengan bahan berkualitas tinggi, nyaman untuk aktivitas sehari-hari.',
            'stock' => 50
        ]);

        ProductVariant::insert([
            [
                'product_id' => $p11->id,
                'color' => 'BLACK',
                'image_url' => 'img/produk/BOXY HITAM 330.jpg',
                'image_hover_url' => 'img/produk/BOXY HITAM 330.jpg',
                'price' => 184000,
                'stock' => 25,
            ],
            [
                'product_id' => $p11->id,
                'color' => 'BROKEN WHITE',
                'image_url' => 'img/produk/KAOS BOXY.jpg',
                'image_hover_url' => 'img/produk/KAOS BOXY B.jpg',
                'price' => 184000,
                'stock' => 25,
            ],
            [
                'product_id' => $p11->id,
                'color' => 'DARK GREY',
                'image_url' => 'img/produk/BOXY HITAM 330.jpg',
                'image_hover_url' => 'img/produk/BOXY HITAM 330.jpg',
                'price' => 184000,
                'stock' => 25,
            ],
        ]);

        $p5 = Product::create([
            'category_id' => 5, // sweatpants
            'name' => 'GLOOMIE SUNDAY - SWEATPANTS BAGGY',
            'slug' => 'sweatpants',
            'price' => 178900,
            'image_url' => 'img/produk/SP MISTY.jpg',
            'image_hover_url' => 'img/produk/SP MISTY B.jpg',
            'description' => 'Gloomie Sunday Original Indonesian Apparel. Fitting: Boxy Fit (Unisex). Material: Cotton Fleece (80% Cotton, 20% Polyester).',
            'stock' => 50
        ]);

        ProductVariant::insert([
            [
                'product_id' => $p5->id,
                'color' => 'BLACK',
                'image_url' => 'img/produk/BOXY HITAM 330.jpg',
                'image_hover_url' => 'img/produk/BOXY HITAM 330.jpg',
                'price' => 184000,
                'stock' => 25,
            ],
            [
                'product_id' => $p5->id,
                'color' => 'MISTY GREY',
                'image_url' => 'img/produk/KAOS BOXY.jpg',
                'image_hover_url' => 'img/produk/KAOS BOXY B.jpg',
                'price' => 184000,
                'stock' => 25,
            ],
            [
                'product_id' => $p5->id,
                'color' => 'DARK GREY',
                'image_url' => 'img/produk/BOXY HITAM 330.jpg',
                'image_hover_url' => 'img/produk/BOXY HITAM 330.jpg',
                'price' => 184000,
                'stock' => 25,
            ],
            [
                'product_id' => $p5->id,
                'color' => 'DARK BROWN',
                'image_url' => 'img/produk/BOXY HITAM 330.jpg',
                'image_hover_url' => 'img/produk/BOXY HITAM 330.jpg',
                'price' => 184000,
                'stock' => 25,
            ],
        ]);


        Product::create([
            'category_id' => 10, // jersey 
            'name' => 'GLOOMIE SUNDAY - SKOOLYARD JERSEY',
            'slug' => 'jersey',
            'price' => 129000,
            'image_url' => 'img/produk/JERSEY.jpg',
            'image_hover_url' => 'img/produk/JERSEY B.jpg',
            'description' => 'Hoodie lengan pendek yang nyaman dengan desain modern untuk tampilan kasual.',
            'stock' => 50
        ]);
        
        Product::create([
            'category_id' => 13, // tshirt boxy printing
            'name' => 'GLOOMIE SUNDAY - TSHIRT BOXY FAREWELL',
            'slug' => 't-shirt-boxy-printing',
            'price' => 79000,
            'image_url' => 'img/produk/FAREWELL.jpg',
            'image_hover_url' => 'img/produk/FAREWELL B.jpg',
            'description' => 'T-Shirt Boxy Printing - FAREWELL',
            'stock' => 50
        ]);

        $p7 = Product::create([
            'category_id' => 7, // boxy zipper
            'name' => 'GLOOMIE SUNDAY - HOODIE BOXY ZIPPER',
            'slug' => 'boxy-zipper',
            'price' => 239000,
            'image_url' => 'img/produk/ZIPPER.jpg',
            'image_hover_url' => 'img/produk/ZIPPER B.jpg',
            'description' => 'Kupluk beanie untuk melengkapi gayamu.',
            'stock' => 100
        ]);
        
        ProductVariant::insert([
            [
                'product_id' => $p7->id,
                'color' => 'BLACK',
                'image_url' => 'img/produk/BOXY HITAM 330.jpg',
                'image_hover_url' => 'img/produk/BOXY HITAM 330.jpg',
                'price' => 184000,
                'stock' => 25,
            ],
            [
                'product_id' => $p7->id,
                'color' => 'MISTY GREY',
                'image_url' => 'img/produk/KAOS BOXY.jpg',
                'image_hover_url' => 'img/produk/KAOS BOXY B.jpg',
                'price' => 184000,
                'stock' => 25,
            ],
            [
                'product_id' => $p7->id,
                'color' => 'DARK GREY',
                'image_url' => 'img/produk/BOXY HITAM 330.jpg',
                'image_hover_url' => 'img/produk/BOXY HITAM 330.jpg',
                'price' => 184000,
                'stock' => 25,
            ],
            [
                'product_id' => $p7->id,
                'color' => 'DARK BROWN',
                'image_url' => 'img/produk/BOXY HITAM 330.jpg',
                'image_hover_url' => 'img/produk/BOXY HITAM 330.jpg',
                'price' => 184000,
                'stock' => 25,
            ],
            [
                'product_id' => $p7->id,
                'color' => 'ROYAL BLUE',
                'image_url' => 'img/produk/BOXY HITAM 330.jpg',
                'image_hover_url' => 'img/produk/BOXY HITAM 330.jpg',
                'price' => 184000,
                'stock' => 25,
            ],
        ]);

        Product::create([
            'category_id' => 9, // sweatpants breaker
            'name' => 'GLOOMIE SUNDAY - BREAKER SWEATPANTS',
            'slug' => 'breaker-sweatpants',
            'price' => 199000,
            'image_url' => 'img/produk/SP BREAKER.jpg',
            'image_hover_url' => 'img/produk/SP BREAKER.jpg',
            'description' => 'Hoodie dengan ritsleting dan potongan boxy yang nyaman.',
            'stock' => 50
        ]);

        Product::create([
            'category_id' => 9, // crewneck breaker
            'name' => 'GLOOMIE SUNDAY - BREAKER CREWNECK',
            'slug' => 'breaker-crewneck',
            'price' => 199000,
            'image_url' => 'img/produk/CREWNECK BREAKER.jpg',
            'image_hover_url' => 'img/produk/CREWNECK BREAKER DETAIL.jpg',
            'description' => 'Hoodie dengan ritsleting dan potongan boxy yang nyaman.',
            'stock' => 50
        ]);
        $p12 = Product::create([
            'category_id' => 12, // hoodie boxy printing
            'name' => 'GLOOMIE SUNDAY - HOODIE BOXY PARTY TO NIGHT',
            'slug' => 'hoodie-boxy-printing',
            'price' => 229000,
            'image_url' => 'img/produk/BOXY HITAM 330.jpg',
            'image_hover_url' => 'img/produk/BOXY HITAM 330.jpg',
            'description' => 'Gloomie Sunday Original Indonesian Apparel. Fitting: Boxy Fit (Unisex). Material: Cotton Fleece (80% Cotton, 20% Polyester).',
            'stock' => 50
        ]);

        ProductVariant::insert([
            [
                'product_id' => $p12->id,
                'color' => 'BLACK',
                'image_url' => 'img/produk/BOXY HITAM 330.jpg',
                'image_hover_url' => 'img/produk/BOXY HITAM 330.jpg',
                'price' => 229000,
                'stock' => 25,
            ],
            [
                'product_id' => $p12->id,
                'color' => 'NAVY',
                'image_url' => 'img/produk/KAOS BOXY.jpg',
                'image_hover_url' => 'img/produk/KAOS BOXY B.jpg',
                'price' => 229000,
                'stock' => 25,
            ],
        ]);
        $p12 = Product::create([
            'category_id' => 12, // hoodie boxy printing
            'name' => 'GLOOMIE SUNDAY - HOODIE BOXY YOUR EVERYTHING',
            'slug' => 'hoodie-boxy-printing',
            'price' => 204000,
            'image_url' => 'img/produk/BOXY HITAM 330.jpg',
            'image_hover_url' => 'img/produk/BOXY HITAM 330.jpg',
            'description' => 'Gloomie Sunday Original Indonesian Apparel. Fitting: Boxy Fit (Unisex). Material: Cotton Fleece (80% Cotton, 20% Polyester).',
            'stock' => 50
        ]);

        ProductVariant::insert([
            [
                'product_id' => $p12->id,
                'color' => 'BROKEN WHITE',
                'image_url' => 'img/produk/BOXY HITAM 330.jpg',
                'image_hover_url' => 'img/produk/BOXY HITAM 330.jpg',
                'price' => 204000,
                'stock' => 25,
            ],
            [
                'product_id' => $p12->id,
                'color' => 'BLACK',
                'image_url' => 'img/produk/KAOS BOXY.jpg',
                'image_hover_url' => 'img/produk/KAOS BOXY B.jpg',
                'price' => 204000,
                'stock' => 25,
            ],
        ]);

        $p4 = Product::create([
            'category_id' => 1, // boxy heavyweight
            'name' => 'GLOOMIE SUNDAY - HOODIE BOXY HEAVYWEIGHT',
            'slug' => 't-shirt-boxy',
            'price' => 68000,
            'image_url' => 'img/produk/BOXY HITAM 330.jpg',
            'image_hover_url' => 'img/produk/BOXY HITAM 330.jpg',
            'description' => 'Gloomie Sunday Original Indonesian Apparel. Fitting: Boxy Fit (Unisex). Material: Cotton Fleece (80% Cotton, 20% Polyester).',
            'stock' => 50
        ]);

        ProductVariant::insert([
            [
                'product_id' => $p1->id,
                'color' => 'BLACK',
                'image_url' => 'img/produk/BOXY HITAM 330.jpg',
                'image_hover_url' => 'img/produk/BOXY HITAM 330.jpg',
                'price' => 184000,
                'stock' => 25,
            ],
            [
                'product_id' => $p1->id,
                'color' => 'BROKEN WHITE',
                'image_url' => 'img/produk/KAOS BOXY.jpg',
                'image_hover_url' => 'img/produk/KAOS BOXY B.jpg',
                'price' => 184000,
                'stock' => 25,
            ],
            [
                'product_id' => $p1->id,
                'color' => 'DARK GREY',
                'image_url' => 'img/produk/BOXY HITAM 330.jpg',
                'image_hover_url' => 'img/produk/BOXY HITAM 330.jpg',
                'price' => 184000,
                'stock' => 25,
            ],
            [
                'product_id' => $p1->id,
                'color' => 'NAVY',
                'image_url' => 'img/produk/KAOS BOXY.jpg',
                'image_hover_url' => 'img/produk/KAOS BOXY B.jpg',
                'price' => 184000,
                'stock' => 25,
            ],
            [
                'product_id' => $p1->id,
                'color' => 'MISTY GREY',
                'image_url' => 'img/produk/BOXY HITAM 330.jpg',
                'image_hover_url' => 'img/produk/BOXY HITAM 330.jpg',
                'price' => 184000,
                'stock' => 25,
            ],
            [
                'product_id' => $p1->id,
                'color' => 'DARK BROWN',
                'image_url' => 'img/produk/KAOS BOXY.jpg',
                'image_hover_url' => 'img/produk/KAOS BOXY B.jpg',
                'price' => 184000,
                'stock' => 25,
            ],
            [
                'product_id' => $p1->id,
                'color' => 'ROYAL BLUE',
                'image_url' => 'img/produk/KAOS BOXY.jpg',
                'image_hover_url' => 'img/produk/KAOS BOXY B.jpg',
                'price' => 184000,
                'stock' => 25,
            ],
        ]);

    }
}