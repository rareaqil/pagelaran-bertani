<?php

namespace App\Http\Controllers;

use App\Http\Controllers\StockMovementController;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Voucher;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Binafy\LaravelCart\Models\Cart;

class CartController extends Controller
{
    // Tampilkan halaman cart
    public function showPage()
    {
        $userId = Auth::id() ?? redirect('/login')->send();
        $cart = Cart::firstOrCreate(['user_id' => $userId]);
        $items = $cart->items;
        $total = $this->getCartTotal($items);

        $products = Product::all();

        return view('cart.index', compact('items', 'total', 'products'));
    }

    // Tambah item ke cart (AJAX)
    public function addItem(Request $request)
    {
        $userId = Auth::id() ?? redirect('/login')->send();
        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        $product = Product::find($request->id);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found']);
        }

        $existing = $cart
            ->items()
            ->where('itemable_id', $product->id)
            ->where('itemable_type', get_class($product))
            ->first();

        $newQty = ($existing->quantity ?? 0) + ($request->quantity ?? 1);

        // Cek stok tersedia (dikurangi yang di-hold)
        if ($newQty > $product->available_stock) {
            return response()->json([
                'success' => false,
                'message' => "Stok tidak cukup, tersedia: {$product->available_stock}",
            ]);
        }

        if ($existing) {
            $existing->quantity = $newQty;
            $existing->save();
        } else {
            $cart->storeItem([
                'itemable' => $product,
                'quantity' => $request->quantity ?? 1,
            ]);
        }

        return response()->json([
            'success' => true,
            'cart' => $this->formatCart($cart),
            'voucher' => $this->recalcVoucher($cart),
        ]);
    }

    // Hapus item tertentu (AJAX)
    public function removeItem($id)
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id() ?? redirect('/login')->send()]);
        $item = $cart->items()->where('id', $id)->first();
        if ($item) {
            $item->delete();
        }

        return response()->json([
            'success' => true,
            'cart' => $this->formatCart($cart),
        ]);
    }

    public function applyVoucher(Request $request)
    {
        $code = $request->code;

        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Harus login']);
        }
        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        $total = $this->getCartTotal($cart->items);

        $voucher = Voucher::where('code', $request->code)->first();
        // dd($voucher->all());
        if (!$voucher || !$voucher->isValid($total)) {
            return response()->json([
                'success' => false,
                'message' => 'Voucher tidak valid atau tidak memenuhi syarat',
            ]);
        }

        if ($total < $voucher->min_order_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal order tidak terpenuhi',
            ]);
        }

        // Hitung diskon berdasarkan type/value
        $discount = $voucher->getDiscount($total);

        // Batasi jika ada max_discount (opsional)
        $discount = min($discount, $voucher->max_discount ?? $discount);

        return response()->json([
            'success' => true,
            'cart' => $this->formatCart($cart),
            'voucher' => [
                'id' => $voucher->id,
                'code' => $voucher->code,
                'discount' => $discount,
            ],
        ]);
    }

    // public function removeVoucher()
    // {
    //     $cart = Cart::firstOrCreate([
    //         'user_id' => auth()->id() ?? 1
    //     ]);

    //     // jika Anda menyimpan relasi voucher di cart/order, kosongkan
    //     if (method_exists($cart, 'voucher')) {
    //         $cart->voucher()->dissociate();
    //         $cart->save();
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'cart'    => $this->formatCart($cart)
    //     ]);
    // }

    // Hapus semua item (AJAX)
    public function clear()
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id() ?? redirect('/login')->send()]);
        $cart->emptyCart();

        return response()->json([
            'success' => true,
            'cart' => $this->formatCart($cart),
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
            $total += $price * $qty * (1 - $discount);
        }
        return $total;
    }

    // Helper: format cart untuk AJAX
    protected function formatCart($cart)
    {
        $cart->load('items.itemable'); // pastikan itemable ter-load
        $items = $cart->items->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->itemable->name,
                'price' => $item->itemable->getPrice(),
                'quantity' => $item->quantity,
                'discount' => $item->discount ?? 0,
            ];
        });
        return ['items' => $items];
    }

    // Update quantity item
    public function updateItemQty(Request $request, $id)
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id() ?? redirect('/login')->send()]);
        $item = $cart->items()->where('id', $id)->first();
        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item not found']);
        }

        $quantity = intval($request->quantity);
        if ($quantity < 1) {
            $quantity = 1;
        }

        $availableStock = $item->itemable->available_stock; // method getAvailableStockAttribute
        if ($quantity > $availableStock) {
            return response()->json([
                'success' => false,
                'message' => "Quantity exceeds available stock ({$availableStock})",
            ]);
        }

        $item->quantity = $quantity;
        $item->save();

        return response()->json([
            'success' => true,
            'cart' => $this->formatCart($cart),
            'voucher' => $this->recalcVoucher($cart),
        ]);
    }

    protected function recalcVoucher($cart)
    {
        $voucherAmount = null;
        if ($cart->voucher) {
            $total = $this->getCartTotal($cart->items);
            $discount = $cart->voucher->getDiscount($total);
            $voucherAmount = min($discount, $cart->voucher->max_discount ?? $discount);
            return [
                'id' => $cart->voucher->id,
                'code' => $cart->voucher->code,
                'discount' => $voucherAmount,
            ];
        }
        return null;
    }

    public function checkout(Request $request)
    {
        $userId = auth()->id(); // pasti ada
        if (!$userId) {
            return response()->json([
                'success' => false,
                'redirect' => url('/login'),
                'message' => 'Silakan login untuk melanjutkan.',
            ]);
        }

        $user = auth()->user();
        $address = $user->primaryAddress;

        if (
            !$address ||
            empty($address->address1) ||
            empty($address->province_name) ||
            empty($address->regency_name) ||
            empty($address->district_name) ||
            empty($address->village_name) ||
            empty($address->postcode) ||
            empty($user->phone)
        ) {
            return response()->json([
                'success' => false,
                'redirect' => url('/profile'),
                'message' => 'Profil belum lengkap. Pastikan alamat dan nomor telepon sudah terisi.',
            ]);
        }
        $cart = Cart::firstOrCreate(['user_id' => $userId]);
        $items = $cart->items;

        if ($items->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Cart kosong']);
        }

        // Hitung subtotal & cek stok
        $subtotal = 0;
        foreach ($items as $item) {
            $product = $item->itemable;

            if ($product->available_stock < $item->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Stok {$product->name} tidak cukup. Tersedia: {$product->available_stock}",
                ]);
            }

            $subtotal += $product->getPrice() * $item->quantity * (1 - ($item->discount ?? 0));
        }

        // Ambil voucher dari request
        $voucherId = $request->voucher['id'] ?? null;
        $discount = $request->voucher['discount'] ?? 0;
        $voucher = $voucherId ? Voucher::find($voucherId) : null;

        // Cek voucher valid
        if ($voucher && !$voucher->isValid($subtotal)) {
            return response()->json(['success' => false, 'message' => 'Voucher tidak valid atau kadaluarsa']);
        }

        // Ambil admin fee
        $adminFee = (int) Setting::getValue('admin_fee', 2000);

        // dd($adminFee);

        // Buat order
        $order = Order::create([
            'user_id' => $userId,
            'total_amount' => $subtotal - $discount + $adminFee,
            'status' => OrderStatus::Pending->value,
            'voucher_id' => $voucherId,
            'discount_amount' => $discount,
            'admin_fee' => $adminFee,
        ]);

        // Simpan order_items & hold stock
        $stockController = new StockMovementController();
        foreach ($items as $item) {
            $product = $item->itemable;

            $order->items()->create([
                'product_id' => $product->id,
                'quantity' => $item->quantity,
                'price' => $product->getPrice(),
            ]);

            // Stock Movement hold
            $holdRequest = new Request([
                'product_id' => $product->id,
                'quantity' => $item->quantity,
                'reference_type' => 'Order',
                'reference_id' => $order->order_id,
            ]);

            $stockController->hold($holdRequest);
        }

        // Kosongkan cart
        $cart->emptyCart();

        if ($user->isAdmin()) {
            $redirectUrl = url('/backend/orders/' . $order->order_id);
        } else {
            $redirectUrl = url('/orders/' . $order->order_id);
        }

        return response()->json([
            'success' => true,
            'order_id' => $order->order_id,
            'redirect' => $redirectUrl,
        ]);
    }
}
