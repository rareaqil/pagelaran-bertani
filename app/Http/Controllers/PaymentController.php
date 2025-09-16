<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

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
}
