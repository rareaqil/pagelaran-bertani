<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        // Cek dulu jika sudah ada
        if (!DB::table('settings')->where('key', 'admin_fee')->exists()) {
            DB::table('settings')->insert([
                'key'        => 'admin_fee',
                'value'      => '2000', // default 2000
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Bisa ditambah setting lain di sini, misal:
        /*
        DB::table('settings')->insert([
            'key' => 'shipping_fee',
            'value' => '5000',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        */

         if (!DB::table('settings')->where('key', 'midtrans_is_production')->exists()) {
            DB::table('settings')->insert([
                'key'        => 'midtrans_is_production',
                'value'      => env('MIDTRANS_IS_PRODUCTION', false),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
