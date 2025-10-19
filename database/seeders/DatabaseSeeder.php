<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'superadmin@example.com',
            'password' => 'password123',
            'role' => 'super_admin',
        ]);

        User::create([
            'first_name' => 'Toko',
            'last_name' => 'Admin',
            'email' => 'admintoko@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin_toko',
        ]);

        User::create([
            'first_name' => 'Kebun',
            'last_name' => 'Admin',
            'email' => 'adminkebun@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin_kebun',
        ]);

        User::create([
            'first_name' => 'Regular',
            'last_name' => 'User',
            'email' => 'user@example.com',
            'password' => 'password123',
            'role' => 'user',
        ]);

        // --- Another Seeder ---
        $this->call([FruitTypeSeeder::class, SettingsSeeder::class, IndoRegionSeeder::class]);
    }
}
