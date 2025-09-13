<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Post;
use App\Http\Requests\PostRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->paginate(10);
        return view('backend.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('backend.posts.form');
    }

    public function store(PostRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 'public');
        }

        $data['slug']    = Str::slug($data['title']);
        $data['user_id'] = auth()->id();

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

        $data['slug'] = Str::slug($data['title']);
        $post->update($data);

        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }
}
