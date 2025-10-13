<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
use App\Models\Testimonial;

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

        $allTestimonials = Testimonial::with(['user', 'product'])
            ->where('is_approved', true)
            ->orderBy('rating', 'desc')
            ->get();

        $uniqueTestimonials = $allTestimonials->unique('product_id')->take(4);

        // Sisanya tetap dipakai untuk modal See More
        $remainingTestimonials = $allTestimonials->diff($uniqueTestimonials);

        // Gabungkan supaya modal tetap menampilkan semua
        $testimonials = $uniqueTestimonials->concat($remainingTestimonials);

        $email = Setting::where('key', 'contact_email')->value('value');

        return view('frontend.home', compact('products', 'testimonials', 'email', 'uniqueTestimonials'));
    }
}
