<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request  $request)
    {
        // $sort = $request->query('sort', 'id'); // default sort by id
        // $direction = $request->query('direction', 'asc'); // default ascending

        // $perPage = request()->query('perPage', 10); // default 10
        // $posts = Post::latest()->paginate($perPage)->withQueryString();
    
        // return view('backend.posts.index', compact('posts'));



        $sort = $request->query('sort', 'id'); // default sort by id
        $direction = $request->query('direction', 'asc'); // default ascending

        // --- Pagination ---
        $perPage = $request->query('perPage', 10); // default 10
        $page = $request->query('page', 1);

        // Hitung total items untuk reset page jika perlu
        $totalItems = Post::count();
        $totalPages = ceil($totalItems / $perPage);
        if ($page > $totalPages) {
            $page = 1;
        }

        // Ambil data dengan sort & paginate
        $posts = Post::orderBy($sort, $direction)
            ->paginate($perPage, ['*'], 'page', $page)
            ->withQueryString();

        return view('backend.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('backend.posts.form');
    }

    public function store(PostRequest $request)
    {
        $data = $request->validated();

        $data['image'] = $request->input('image');

        $data['slug'] = $this->generateUniqueSlug($data['name']);
        $data['created_by'] = auth()->id();
        $data['created_by_name'] = auth()->user()->first_name . ' ' . auth()->user()->last_name;
         if ($data['status'] === 'published' && empty($data['published_at'])) {
                $data['published_at'] = now();
            }

        Post::create($data);

        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }

    public function show(Post $post)
    {
        return view('backend.posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        return view('backend.posts.form', compact('post'));
    }

    public function update(PostRequest $request, Post $post)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $data['slug'] = $this->generateUniqueSlug($data['name'], $post->id);
        $data['updated_by'] = auth()->id();
        
        if ($data['status'] === 'published' && $post->status !== 'published') {
            $data['published_at'] = now();
        }

        $post->update($data);

        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->deleted_by = auth()->id();
        $post->save();
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }


    // Hellper

    /** 
     * generateUniqueSlug
     * Usage: 
     * $data['slug'] = $this->generateUniqueSlug($data['name']);
     * 
     */
private function generateUniqueSlug($name, $id = null)
{
    $slug = Str::slug($name);
    $originalSlug = $slug;
    $counter = 1;

    // cek apakah slug sudah ada
    while (
        Post::where('slug', $slug)
            ->when($id, fn($q) => $q->where('id', '!=', $id)) // biar kalau update ga bentrok dengan dirinya sendiri
            ->exists()
    ) {
        $slug = $originalSlug . '-' . $counter++;
    }

    return $slug;
}
}




