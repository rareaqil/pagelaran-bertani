<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    // Lihat semua movement
    public function index()
    {
        $movements = StockMovement::with('product', 'relatedMovement')->latest()->get();
        return response()->json($movements);
    }

    // Checkout: buat hold
    public function hold(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reference_type' => 'nullable|string',
            'reference_id' => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($request->quantity > $product->available_stock) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Stok tidak cukup',
                    'available_stock' => $product->available_stock,
                ],
                422,
            );
        }

        $movement = StockMovement::create([
            'product_id' => $product->id,
            'type' => 'hold',
            'quantity' => $request->quantity,
            'reference_type' => $request->reference_type,
            'reference_id' => $request->reference_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Stok di-hold',
            'data' => $movement,
        ]);
    }

    // Konfirmasi pembayaran → ubah hold jadi out
    public function confirmPayment($holdId)
    {
        $hold = StockMovement::findOrFail($holdId);
        if ($hold->type !== 'hold') {
            return response()->json(['success' => false, 'message' => 'Movement bukan hold'], 422);
        }

        $product = $hold->product;

        // Kurangi stok fisik
        $product->decrement('stock', $hold->quantity);

        // Tambah stok Voucher digunakan
        if ($hold->reference instanceof \App\Models\Order) {
            $order = $hold->reference;

            if ($order->voucher) {
                $voucher = $order->voucher;
                $voucher->increment('used_count');

                if (!is_null($voucher->max_usage) && $voucher->used_count >= $voucher->max_usage) {
                    $voucher->update(['is_active' => false]);
                }
            }
        }

        // Update movement jadi out
        $hold->update(['type' => 'out']);

        return response()->json(['success' => true, 'message' => 'Pembayaran sukses, stok dikurangi']);
    }

    // Batalkan hold → ubah jadi reversal
    public function cancelHold($holdId)
    {
        $hold = StockMovement::findOrFail($holdId);
        if ($hold->type !== 'hold') {
            return response()->json(['success' => false, 'message' => 'Movement bukan hold'], 422);
        }

        $hold->update(['type' => 'reversal']);

        return response()->json(['success' => true, 'message' => 'Hold dibatalkan']);
    }

    // Tambah stok manual / in
    public function addStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reference_type' => 'nullable|string',
            'reference_id' => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);

        $movement = StockMovement::create([
            'product_id' => $product->id,
            'type' => 'in',
            'quantity' => $request->quantity,
            'reference_type' => $request->reference_type,
            'reference_id' => $request->reference_id,
        ]);

        $product->increment('stock', $request->quantity);

        return response()->json([
            'success' => true,
            'message' => 'Stok berhasil ditambahkan',
            'data' => $movement,
        ]);
    }

    // Kurangi stok manual / in
    public function minStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reference_type' => 'nullable|string',
            'reference_id' => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);

        $movement = StockMovement::create([
            'product_id' => $product->id,
            'type' => 'out',
            'quantity' => $request->quantity,
            'reference_type' => $request->reference_type,
            'reference_id' => $request->reference_id,
        ]);

        $product->decrement('stock', $request->quantity);

        return response()->json([
            'success' => true,
            'message' => 'Stok berhasil dikurangi',
            'data' => $movement,
        ]);
    }

    public function adjustStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:0', // stok baru minimal 0
        ]);

        $product = Product::findOrFail($request->product_id);

        // Hitung total hold untuk produk ini
        $totalHold = StockMovement::where('product_id', $product->id)->where('type', 'hold')->sum('quantity');

        $newStock = $request->quantity;

        // Validasi: stok baru >= total hold
        if ($newStock < $totalHold) {
            return response()->json(
                [
                    'success' => false,
                    'message' => "Stok tidak boleh kurang dari stock yang di-hold ({$totalHold})",
                ],
                422,
            );
        }

        $oldStock = $product->stock;

        // Update stok langsung
        $product->stock = $newStock;
        $product->save();

        // Log movement (optional)
        $movementType = $newStock > $oldStock ? 'in' : ($newStock < $oldStock ? 'out' : 'none');
        $movementQty = abs($newStock - $oldStock);

        if ($movementType !== 'none') {
            StockMovement::create([
                'product_id' => $product->id,
                'reference_type' => 'Adjustment',
                'type' => $movementType,
                'quantity' => $movementQty,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Adjustment stok berhasil',
            'data' => [
                'old_stock' => $oldStock,
                'new_stock' => $newStock,
                'total_hold' => $totalHold,
            ],
        ]);
    }
}
