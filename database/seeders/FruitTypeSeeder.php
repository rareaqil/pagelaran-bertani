<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FruitTypeSeeder extends Seeder
{
    public function run(): void
    {
        $fruits = [
            ['name' => 'Melon', 'slug' => Str::slug('Melon'), 'is_active' => 1],
            ['name' => 'Jeruk', 'slug' => Str::slug('Jeruk'), 'is_active' => 1],
            ['name' => 'Semangka', 'slug' => Str::slug('Semangka'), 'is_active' => 1],
            ['name' => 'Apel', 'slug' => Str::slug('Apel'), 'is_active' => 1],
        ];

        foreach ($fruits as $fruit) {
            DB::table('fruit_types')->insert([
                'name'       => $fruit['name'],
                'slug'       => $fruit['slug'],
                'is_active'  => $fruit['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}