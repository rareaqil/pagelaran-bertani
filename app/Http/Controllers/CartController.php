<?php

namespace App\Http\Controllers;

use App\Models\Product; // pastikan import model Product
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

        $products = Product::all(); // ambil semua product dari DB

        return view('cart.index', compact('items', 'total', 'products'));
    }

    // Tambah item ke cart
    public function addItem(Request $request)
    {
        $userId = Auth::id() ?? 1;
        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        $product = Product::find($request->id);
        if (!$product) return redirect()->route('cart.show')->with('error', 'Product not found');

        $cart->storeItem([
            'itemable' => $product,
            'quantity' => $request->quantity ?? 1
        ]);

        return redirect()->route('cart.show')->with('success', 'Item added to cart');
    }

    // Terapkan kupon
    public function applyCoupon(Request $request)
    {
        $discount = $request->discount; // misal 0.1 = 10%
        $cart = Cart::firstOrCreate(['user_id' => Auth::id() ?? 1]);

        foreach ($cart->items as $item) {
            $item->discount = $discount;
            $item->save();
        }

        return redirect()->route('cart.show')->with('success', 'Coupon applied');
    }

    // Hapus semua item
    public function clear()
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id() ?? 1]);
        $cart->emptyCart();

        return redirect()->route('cart.show')->with('success', 'Cart cleared');
    }

    // Hapus item tertentu
    public function removeItem($id)
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id() ?? 1]);
        $cartItem = $cart->items()->where('id', $id)->first();
        if ($cartItem) $cartItem->delete();

        return redirect()->route('cart.show')->with('success', 'Item removed');
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
}
