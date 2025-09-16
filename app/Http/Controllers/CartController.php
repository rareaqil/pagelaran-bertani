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
    // public function applyVoucher(Request $request)
    // {
    //     $code = $request->code;
    //     $cart = Cart::firstOrCreate(['user_id' => Auth::id() ?? 1]);

    //     $voucher = \App\Models\Voucher::where('code', $code)
    //         ->where('is_active', true)
    //         ->where(function ($q) {
    //             $q->whereNull('start_date')->orWhere('start_date', '<=', now());
    //         })
    //         ->where(function ($q) {
    //             $q->whereNull('end_date')->orWhere('end_date', '>=', now());
    //         })
    //         ->first();

    //     if (!$voucher) {
    //         return response()->json(['success' => false, 'message' => 'Voucher tidak valid']);
    //     }

    //     $total = $this->getCartTotal($cart->items);
    //     if ($total < $voucher->min_order_amount) {
    //         return response()->json(['success' => false, 'message' => 'Minimal order tidak terpenuhi']);
    //     }

    //     // simpan voucher ke cart
    //     $cart->voucher_id = $voucher->id;
    //     $cart->save();

    //     return response()->json([
    //         'success' => true,
    //         'cart' => $this->formatCart($cart, $voucher)
    //     ]);
    // }
    public function applyVoucher(Request $request)
    {
        $code = $request->code;
        $cart = Cart::firstOrCreate(['user_id' => Auth::id() ?? 1]);

        $voucher = \App\Models\Voucher::where('code', $code)
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now());
            })
            ->first();

        if (!$voucher) {
            return response()->json([
                'success' => false, 
                'message' => 'Voucher tidak valid'
            ]);
        }

        $total = $this->getCartTotal($cart->items);

        if ($total < $voucher->min_order_amount) {
            return response()->json([
                'success' => false, 
                'message' => 'Minimal order tidak terpenuhi'
            ]);
        }

        // Hitung diskon berdasarkan type/value
        $discount = 0;
        if ($voucher->type === 'percent') {
            $discount = $total * ($voucher->value ?? 0) / 100;
        } elseif ($voucher->type === 'fixed') {
            $discount = $voucher->value ?? 0;
        }

        // Batasi jika ada max_discount (opsional)
        $discount = min($discount, $voucher->max_discount ?? $discount);

        return response()->json([
            'success' => true,
            'cart' => $this->formatCart($cart),
            'voucher' => [
                'id' => $voucher->id,
                'code' => $voucher->code,
                'discount' => $discount
            ]
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

    // Update quantity item
    public function updateItemQty(Request $request, $id)
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id() ?? 1]);
        $item = $cart->items()->where('id', $id)->first();
        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item not found']);
        }

        $quantity = intval($request->quantity);
        if ($quantity < 1) $quantity = 1;

        $item->quantity = $quantity;
        $item->save();

        return response()->json([
            'success' => true,
            'cart' => $this->formatCart($cart)
        ]);
}


public function checkout(Request $request)
{

    $userId = auth()->id() ?? 1;
    $cart = Cart::firstOrCreate(['user_id' => $userId]);
    $items = $cart->items;

    if ($items->isEmpty()) {
        return response()->json(['success' => false, 'message' => 'Cart kosong']);
    }

    // Hitung subtotal
    $subtotal = 0;
    foreach ($items as $item) {
        $subtotal += $item->itemable->getPrice() * $item->quantity * (1 - ($item->discount ?? 0));
    }

    // Ambil voucher dari request (frontend kirim currentVoucher)
    $voucherId = $request->voucher['id'] ?? null;
    $discount = $request->voucher['discount'] ?? 0;

    // Buat order
    $order = \App\Models\Order::create([
        'user_id' => $userId,
        'total_amount' => $subtotal - $discount,
        'status' => 'pending',
        'voucher_id' => $voucherId,
        'discount_amount' => $discount,
    ]);

    // Simpan order_items
    foreach ($items as $item) {
        $order->items()->create([
            'product_id' => $item->itemable->id,
            'quantity' => $item->quantity,
            'price' => $item->itemable->getPrice(),
        ]);

        // Stock Movement
        \App\Models\StockMovement::create([
            'product_id' => $item->itemable->id,
            'type' => 'out',
            'quantity' => $item->quantity,
            'reference_type' => 'order',
            'reference_id' => $order->id,
        ]);
    }

    // Kosongkan cart
    $cart->emptyCart();

    return response()->json(['success' => true, 'order_id' => $order->id]);
 }


}
