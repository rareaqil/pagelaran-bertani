<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Models\Product;
use App\Http\Controllers\RegionController;

use Illuminate\Http\Request;

// Route::get('/api/provinces', [RegionController::class, 'provinces']);
// Route::get('/api/provinces/{id}', function($id, RegionController $region){
//     $provinces = $region->provinces();
//     return collect($provinces)->firstWhere('id', (int)$id);
// });
// Route::get('/api/cities/{provinceId}', [RegionController::class, 'cities']);
// Route::get('/api/cities/detail/{id}', function($id, RegionController $region){
//     $provinces = Cache::get('cities') ?? [];
//     $allCities = collect([]);
//     foreach(Cache::get('cities') ?? [] as $key => $cities){
//         $allCities = $allCities->merge($cities);
//     }
//     return $allCities->firstWhere('id', (int)$id);
// });
// Route::get('/api/districts/{cityId}', [RegionController::class, 'districts']);
// Route::get('/api/districts/detail/{id}', function($id, RegionController $region){
//     $provinces = Cache::get('cities') ?? [];
//     $allCities = collect([]);
//     foreach(Cache::get('cities') ?? [] as $key => $cities){
//         $allCities = $allCities->merge($cities);
//     }
//     return $allCities->firstWhere('id', (int)$id);
// });
// Route::get('/api/villages/{districtId}', [RegionController::class, 'villages']);
// Route::get('/api/villages/detail/{id}', function($id, RegionController $region){
//     $all = collect([]);
//     foreach(Cache::get('villages') ?? [] as $key => $villages){
//         $all = $all->merge($villages);
//     }
//     return $all->firstWhere('id', (int)$id);
// });


Route::prefix('api')->group(function () {
    Route::get('/provinces', [RegionController::class, 'provinces']);
    Route::get('/regencies/{provinceId}', [RegionController::class, 'regencies']);
    Route::get('/districts/{regencyId}', [RegionController::class, 'districts']);
    Route::get('/villages/{districtId}', [RegionController::class, 'villages']);




Route::get('/products', function(Request $request) {
    $query = $request->get('q', '');
    $products = Product::where('name', 'like', "%$query%")->get();
    return $products;
});

});