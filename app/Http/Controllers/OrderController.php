<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Menampilkan semua order
    public function index()
    {
        $orders = Order::with(['user', 'voucher', 'items', 'payment'])->latest()->get();
        return response()->json($orders);
    }

    // Membuat order baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'        => 'required|exists:users,id',
            'total_amount'   => 'required|numeric|min:0',
            'voucher_id'     => 'nullable|exists:vouchers,id',
            'discount_amount'=> 'nullable|numeric|min:0',
        ]);

        $order = Order::create($validated);

        return response()->json([
            'message' => 'Order created successfully',
            'data'    => $order
        ]);
    }

    // Menampilkan detail order
    public function show(Order $order)
    {
        $order->load(['user', 'voucher', 'items', 'payment']);
        return response()->json($order);
    }

    // Update order (misal update status)
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status'          => 'in:pending,paid,cancelled',
            'total_amount'    => 'numeric|min:0',
            'discount_amount' => 'numeric|min:0',
        ]);

        $order->update($validated);

        return response()->json([
            'message' => 'Order updated successfully',
            'data'    => $order
        ]);
    }

    // Hapus order (soft delete)
    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }
}
