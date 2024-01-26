<?php

namespace Database\Seeders;

use App\Models\Machine;
use App\Models\Product;
use Illuminate\Database\Seeder;

class MachineProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create products
        Product::factory(100)->create();

        // Create machines
        for ($m = 0; $m < 10; $m++) {
            $machine = Machine::factory(1)->create()->first();

            // Attach products to machines
            for ($p = 0; $p < 10; $p++) {
                $product = Product::inRandomOrder()->first();
                $machine->products()->attach($product, ['quantity' => fake()->numberBetween(1, 100)]);
            }

        }
    }
}
