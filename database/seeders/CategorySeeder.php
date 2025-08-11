<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create(['name' => 'Boxy Heavyweight', 'slug' => 'boxy-heavyweight']); // ID: 1
        Category::create(['name' => 'Boxy Standard', 'slug' => 'boxy-standard']);       // ID: 2
        Category::create(['name' => 'Boxy Superweight', 'slug' => 'boxy-superweight']);  // ID: 3
        Category::create(['name' => 'T-Shirt Boxy', 'slug' => 't-shirt-boxy']);         // ID: 4
        Category::create(['name' => 'Sweatpants', 'slug' => 'sweatpants']);             // ID: 5
        Category::create(['name' => 'Boardshort', 'slug' => 'boardshort']);             // ID: 6
        Category::create(['name' => 'Boxy Zipper', 'slug' => 'boxy-zipper']);           // ID: 7
        Category::create(['name' => 'Accessories', 'slug' => 'accessories']);           // ID: 8
        Category::create(['name' => 'BREAKER', 'slug' => 'breaker']);           // ID: 9 
        Category::create(['name' => 'Jersey', 'slug' => 'jersey']);           // ID: 10
        Category::create(['name' => 'Hoodie Boxy Without Pocket', 'slug' => 'boxy-without-pocket']);  //ID: 11
        Category::create(['name' => 'Hooide Boxy Printing', 'slug' => 'hoodie-boxy-printing']);  //ID: 12
        Category::create(['name' => 'T-Shirt Boxy Printing', 'slug' => 't-shirt-boxy-printing']);  //ID: 13
    }
}