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
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak cukup',
                'available_stock' => $product->available_stock
            ], 422);
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
            'data' => $movement
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
            'data' => $movement
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
            'data' => $movement
        ]);
    }
}
