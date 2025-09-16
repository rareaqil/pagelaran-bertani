<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

class StockMovementFactory extends Factory
{
    public function definition(): array
    {
        // pilih type acak
        $type = $this->faker->randomElement(['in', 'out', 'hold', 'reversal']);

        return [
            // pastikan sudah ada ProductFactory agar bisa generate product_id
            'product_id'         => Product::factory(),
            'type'               => $type,
            'quantity'           => $this->faker->numberBetween(1, 20),
            'reference_type'     => $this->faker->optional()->randomElement(['order', 'manual']),
            'reference_id'       => $this->faker->optional()->numberBetween(1, 1000),
            'related_movement_id'=> null, // bisa diisi nanti jika perlu relasi reversal/hold
        ];
    }
}
