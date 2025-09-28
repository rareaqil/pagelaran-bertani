<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OrderProductController extends Controller
{
    public function index()
    {
        $products = Product::with('fruitType')
            ->where('status_active', 1)
            ->get()
            ->toArray();

        return view('frontend.product', compact('products'));
    }
}