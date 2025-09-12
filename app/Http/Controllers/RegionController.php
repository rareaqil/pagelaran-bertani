<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache; // ← tambah ini
use Illuminate\Support\Facades\Http;  // ← tambah ini

class RegionController extends Controller
{
    public function provinces()
    {
        return Cache::remember('provinces', 60*60, function() {
            $response = Http::get('https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json');
            return $response->json();
        });
    }

    public function cities($provinceId)
    {
        return Cache::remember("cities_$provinceId", 60*60, function() use ($provinceId) {
            $response = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/regencies/{$provinceId}.json");
            return $response->json();
        });
    }

    public function districts($cityId)
    {
        return Cache::remember("districts_$cityId", 60*60, function() use ($cityId) {
            $response = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/districts/{$cityId}.json");
            return $response->json();
        });
    }

    public function villages($districtId)
    {
        return Cache::remember("villages_$districtId", 60*60, function() use ($districtId) {
            $response = Http::get("https://www.emsifa.com/api-wilayah-indonesia/api/villages/{$districtId}.json");
            return $response->json();
        });
    }
}
