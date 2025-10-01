<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class LearnController extends Controller
{
    /**
     * Tampilkan daftar produk.
     */
    public function index()
    {
        $fruitTypes = \App\Models\FruitType::whereHas('posts', function ($q) {
            $q->where('status', 'published');
        })
        ->orderBy('name')
        ->get();

        $posts = \App\Models\Post::with('fruitType')
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->get();

        return view('frontend.learn', compact('posts', 'fruitTypes'));
    }

}