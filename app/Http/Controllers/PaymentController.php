<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use App\Models\Payment;
use Illuminate\Http\Request;

use App\Models\Order;

use App\Services\MidtransService;

class PaymentController extends Controller
{
    public function createSnapToken(Order $order)
    {
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = false;
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        // ===> Ambil item detail dari order
        $items = $order->items->map(function ($item) {
            return [
                'id'       => $item->id,
                'price'    => $item->price,
                'quantity' => $item->quantity,
                'name'     => $item->product->name ?? $item->name,
            ];
        })->toArray();

        // ===> Body request Snap
        $params = [
            "transaction_details" => [
                "order_id"     => $order->order_id,   // gunakan order_id unik kita
                "gross_amount" => $order->total,
            ],
            "item_details"       => $items,
            "customer_details"   => [
                "first_name" => $order->user->first_name,
                "last_name"  => $order->user->last_name,
                "email"      => $order->user->email,
                "phone"      => $order->user->phone,
                "billing_address" => [
                    "first_name"  => $order->user->first_name,
                    "last_name"   => $order->user->last_name,
                    "email"       => $order->user->email,
                    "phone"       => $order->user->phone,
                    "address"     => $order->user->primaryAddress->address1 ?? '',
                    "city"        => $order->user->primaryAddress->city ?? '',
                    "postal_code" => $order->user->primaryAddress->postal_code ?? '',
                    "country_code"=> "IDN"
                ],
            ],
            // contoh tambahan optional:
            "enabled_payments" => ["gopay", "bca_va", "shopeepay"],
            "expiry" => [
                "unit"     => "hours",
                "duration" => 2, // transaksi kadaluarsa 2 jam
            ],
            "custom_field1" => "Order from Laravel App",
        ];

        $snapToken = Snap::getSnapToken($params);

        $order->snap_token = $snapToken;
        $order->save();

        return response()->json(['snap_token' => $snapToken]);
    }

    public function index()
    {
        return Payment::with('order')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_gateway' => 'nullable|string',
            'transaction_id' => 'nullable|string',
            'status' => 'nullable|string',
            'amount' => 'required|numeric',
            'raw_response' => 'nullable|json',
        ]);

        return Payment::create($data);
    }

    public function show(Payment $payment)
    {
        return $payment->load('order');
    }

    public function update(Request $request, Payment $payment)
    {
        $data = $request->validate([
            'payment_gateway' => 'nullable|string',
            'transaction_id' => 'nullable|string',
            'status' => 'nullable|string',
            'amount' => 'required|numeric',
            'raw_response' => 'nullable|json',
        ]);

        $payment->update($data);
        return $payment;
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return response()->noContent();
    }


    public function midtransCallback(Request $request, MidtransService $midtrans)
    {

        // Log::info('RAW INPUT', [file_get_contents('php://input')]);
        $notif = $midtrans->notification();

        Log::info('Midtrans raw callback', $request->all());

        if (! $midtrans->isSignatureValid($notif)) {
             Log::warning('Midtrans signature invalid', ['order_id' => $notif->order_id ?? null]);
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        $order = Order::where('order_id', $notif->order_id)->first();
        if (! $order) {
             Log::warning('Midtrans order_id not found', ['order_id' => $notif->order_id ?? null]);

            return response()->json(['message' => 'Order not found'], 404);
        }

        $status = $midtrans->mapStatus($notif);

         Log::info('Midtrans mapped status', [
        'order_id' => $notif->order_id,
        'status'   => $status,
         ]);
        switch ($status) {
            case 'success':
                // $order->update([
                //     'status'         => 'processing',
                //     'payment_status' => 'paid',
                // ]);

                // Update atau buat Payment (ONE TO ONE)
                // $order->payment()->updateOrCreate(
                //     ['order_id' => $order->id],
                //     [
                //         'status'      => 'PAID',
                //         'paid_at'     => now(),
                //         'midtrans_id' => $notif->transaction_id,
                //     ]
                // );
                break;

            case 'pending':
                // $order->update(['payment_status' => 'pending']);
                // $order->payment()->updateOrCreate(
                //     ['order_id' => $order->id],
                //     ['status' => 'PENDING', 'midtrans_id' => $notif->transaction_id]
                // );
                break;

            case 'expire':
                // $order->update(['payment_status' => 'expired']);
                // $order->payment()->updateOrCreate(
                //     ['order_id' => $order->id],
                //     ['status' => 'EXPIRED', 'midtrans_id' => $notif->transaction_id]
                // );
                break;

            case 'cancel':
            case 'failed':
                // $order->update(['payment_status' => $status]);
                // $order->payment()->updateOrCreate(
                //     ['order_id' => $order->id],
                //     ['status' => strtoupper($status), 'midtrans_id' => $notif->transaction_id]
                // );
                break;

            // refund / partial_refund / authorize dapat ditangani serupa
        }

        return response()->json(['success' => true]);
    }


}
