<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Boxy Heavyweight',           'slug' => 'boxy-heavyweight'],     // ID: 1
            ['name' => 'Boxy Standard',              'slug' => 'boxy-standard'],        // ID: 2
            ['name' => 'Boxy Superweight',           'slug' => 'boxy-superweight'],     // ID: 3
            ['name' => 'T-Shirt Boxy',                'slug' => 't-shirt-boxy'],         // ID: 4
            ['name' => 'Sweatpants',                  'slug' => 'sweatpants'],           // ID: 5
            ['name' => 'Boardshort',                  'slug' => 'boardshort'],           // ID: 6
            ['name' => 'Boxy Zipper',                 'slug' => 'boxy-zipper'],          // ID: 7
            ['name' => 'Accessories',                 'slug' => 'accessories'],          // ID: 8
            ['name' => 'Breaker',                     'slug' => 'breaker'],              // ID: 9
            ['name' => 'Jersey',                      'slug' => 'jersey'],               // ID: 10
            ['name' => 'Hoodie Boxy Without Pocket',  'slug' => 'hoodie-without-pocket'],// ID: 11
            ['name' => 'Hoodie Boxy Printing',        'slug' => 'hoodie-boxy-printing'], // ID: 12
            ['name' => 'T-Shirt Boxy Printing',       'slug' => 't-shirt-boxy-printing'],// ID: 13
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(
                ['slug' => $cat['slug']],
                ['name' => $cat['name']]
            );
        }
    }
}
