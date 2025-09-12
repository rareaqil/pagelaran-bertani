<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Binafy\LaravelCart\Models\Cart;

class CartController extends Controller
{
    // Tampilkan halaman cart
    public function showPage()
    {
        $userId = Auth::id() ?? 1;
        $cart = Cart::firstOrCreate(['user_id' => $userId]);
        $items = $cart->items;
        $total = $this->getCartTotal($items);

        $products = Product::all();

        return view('cart.index', compact('items', 'total', 'products'));
    }

    // Tambah item ke cart (AJAX)
    public function addItem(Request $request)
    {
        $userId = Auth::id() ?? 1;
        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        $product = Product::find($request->id);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found']);
        }

        // Cek apakah product sudah ada di cart
        $existing = $cart->items()->where('itemable_id', $product->id)
                                  ->where('itemable_type', get_class($product))
                                  ->first();

        if ($existing) {
            $existing->quantity += $request->quantity ?? 1;
            $existing->save();
        } else {
            $cart->storeItem([
                'itemable' => $product,
                'quantity' => $request->quantity ?? 1
            ]);
        }

        return response()->json([
            'success' => true,
            'cart' => $this->formatCart($cart)
        ]);
    }

    // Hapus item tertentu (AJAX)
    public function removeItem($id)
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id() ?? 1]);
        $item = $cart->items()->where('id', $id)->first();
        if ($item) $item->delete();

        return response()->json([
            'success' => true,
            'cart' => $this->formatCart($cart)
        ]);
    }

    // Terapkan kupon (AJAX)
    public function applyCoupon(Request $request)
    {
        $discount = floatval($request->discount);
        $cart = Cart::firstOrCreate(['user_id' => Auth::id() ?? 1]);

        foreach ($cart->items as $item) {
            $item->discount = $discount;
            $item->save();
        }

        return response()->json([
            'success' => true,
            'cart' => $this->formatCart($cart)
        ]);
    }

    // Hapus semua item (AJAX)
    public function clear()
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id() ?? 1]);
        $cart->emptyCart();

        return response()->json([
            'success' => true,
            'cart' => $this->formatCart($cart)
        ]);
    }

    // Helper: hitung total
    protected function getCartTotal($items)
    {
        $total = 0;
        foreach ($items as $item) {
            $price = $item->itemable->getPrice() ?? 0;
            $qty = $item->quantity ?? 1;
            $discount = $item->discount ?? 0;
            $total += ($price * $qty) * (1 - $discount);
        }
        return $total;
    }

    // Helper: format cart untuk AJAX
    protected function formatCart($cart)
    {
        $cart->load('items.itemable'); // pastikan itemable ter-load
        $items = $cart->items->map(function($item) {
            return [
                'id' => $item->id,
                'name' => $item->itemable->name,
                'price' => $item->itemable->getPrice(),
                'quantity' => $item->quantity,
                'discount' => $item->discount ?? 0
            ];
        });
        return ['items' => $items];
    }
}
