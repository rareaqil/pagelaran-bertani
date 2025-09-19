<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\FruitType;
use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $sort      = $request->query('sort', 'id');
        $direction = $request->query('direction', 'asc');
        $perPage   = $request->query('perPage', 10);
        $page      = $request->query('page', 1);

        $totalItems = Product::count();
        $totalPages = ceil($totalItems / $perPage);
        if ($page > $totalPages) {
            $page = 1;
        }

        $products = Product::orderBy($sort, $direction)
            ->paginate($perPage, ['*'], 'page', $page)
            ->withQueryString();

        return view('backend.products.index', compact('products'));
    }

    public function create()
    {
        $fruits = FruitType::whereNull('deleted_at')->get();
        return view('backend.products.form', compact('fruits'));
    }

    public function store(ProductRequest $request)
    {
        dd($request->all());
        $data = $request->validated();


        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // auto SKU jika tidak diisi
        if (empty($data['sku'])) {
            $data['sku'] = 'SKU-' . strtoupper(Str::random(8));
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        return view('backend.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $fruits = FruitType::whereNull('deleted_at')->get();
        return view('backend.products.form', compact('product','fruits'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        // dd($request->all());
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
