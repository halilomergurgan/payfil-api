<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 1000) as $product) {
            Product::create([
                'name' => $faker->company . ' ' . $faker->word,
                'price' => $faker->randomFloat(2, 10, 1000),
                'description' => $faker->sentence,
            ]);
        }
    }
}
