<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'         => $this->faker->words(2, true),                // nama produk
            'description'  => $this->faker->sentence(),                    // deskripsi singkat
            'price'        => $this->faker->randomFloat(2, 1000, 100000),  // harga 1.000–100.000
            'stock'        => $this->faker->numberBetween(0, 50),          // stok on-hand
            'status_active'=> $this->faker->boolean(90),                   // 90% aktif
            'image'        => $this->faker->imageUrl(640, 480, 'products', true),
            'weight'       => $this->faker->randomFloat(2, 0.1, 5),        // 0.1–5 kg
            'sku'          => 'SKU-'.Str::upper(Str::random(8)),           // kode unik produk
        ];
    }
}
