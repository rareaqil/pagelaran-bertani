<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\StockMovement;
use App\Services\MidtransService;
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




    public function indexView(Request $request)
    {
        // Bisa ditambahkan pagination & search
        $sort      = $request->query('sort', 'created_at');
        $direction = $request->query('direction', 'desc');
        $perPage   = $request->query('perPage', 10);

        $orders = Order::with(['user','voucher','items','payment'])
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();

        return view('backend.orders.index', compact('orders'));
    }

    // public function showView(Order $order)
    // {
    //     $order->load(['user.primaryAddress', 'items.product', 'voucher']);


    //      $holdMovements = StockMovement::where('reference_type', 'order')
    //     ->where('reference_id', $order->order_id)
    //     ->where('type', 'hold')
    //     ->get();

    //     // Hitung subtotal
    //     $subtotal = $order->items->sum(fn($item) => $item->price * $item->quantity);

    //     // Hitung discount
    //     $discountAmount = 0;
    //     if ($order->voucher) {
    //         $discountAmount = $order->voucher->type === 'percentage'
    //             ? $subtotal * ($order->voucher->value / 100)
    //             : $order->voucher->value;
    //     }

    //     // Hitung total
    //     $total = $subtotal - $discountAmount;

    //     return view('backend.orders.show', compact('order', 'subtotal', 'discountAmount', 'total','holdMovements'));
    // }


    public function showView(MidtransService $midtransService, Order $order)
{
    // Load relasi
    $order->load(['user.primaryAddress', 'items.product', 'voucher', 'payment']);

    // Ambil stock hold movement
    $holdMovements = StockMovement::where('reference_type', 'order')
        ->where('reference_id', $order->order_id)
        ->where('type', 'hold')
        ->get();

    // Hitung subtotal
    $subtotal = $order->items->sum(fn($item) => $item->price * $item->quantity);

    // Hitung discount
    $discountAmount = 0;
    if ($order->voucher) {
        $discountAmount = $order->voucher->type === 'percentage'
            ? $subtotal * ($order->voucher->value / 100)
            : $order->voucher->value;
    }

    // Total
    $total = $subtotal - $discountAmount;

    // --- Midtrans Snap Token ---
    $payment = $order->payment;
    if ($payment === null || $payment->status === 'EXPIRED') {
        $midtransData = $midtransService->createSnapToken($order);
        $snapToken = $midtransData['snap_token'];
        // dd($midtransData);
        // Simpan payment baru
        $order->payment()->create([
            'raw_response'  => json_encode($midtransData['params']),
            'snap_token'    => $snapToken,
            'amount'        => $total, // total yang sudah dihitung
            'status'        => 'PENDING',
            'payment_gateway' => 'midtrans',
        ]);
    } else {
        //  $snapToken = $midtransService->createSnapToken($order);

        // dd($snapToken);
        $snapToken = $payment->snap_token;
    }

    return view('backend.orders.show', compact(
        'order',
        'subtotal',
        'discountAmount',
        'total',
        'holdMovements',
        'snapToken'
    ));
}



}
