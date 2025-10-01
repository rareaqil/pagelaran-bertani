<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function home()
    {
        return view('frontend.home');
    }

    public function learn()
    {
        return view('frontend.learn');
    }

    public function product()
    {
        return view('frontend.product');
    }

    public function contact()
    {
        return view('frontend.contact-us');
    }

    public function OrderHistory()
    {
        return view('frontend.order-history');
    }
}