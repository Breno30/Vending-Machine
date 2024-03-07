<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        
        $relation = DB::table('machine_product')->inRandomOrder()->first();

        return [
            'machine_product_id' => $relation->id,
            'type' => 'pix',
            'identifier' => fake()->unique()->randomNumber
        ];
        
    }
}
