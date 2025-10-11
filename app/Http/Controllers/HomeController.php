<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Testimonial;
use App\Models\Setting;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::where('status_active', 1)
            ->orderBy('id', 'desc')
            ->take(4)
            ->get();

        $testimonials = Testimonial::with(['user', 'product'])
            ->where('is_approved', true)
            ->latest()
            ->get();

        $email = Setting::where('key', 'contact_email')->value('value');

        return view('frontend.home', compact('products', 'testimonials', 'email'));
    }
}