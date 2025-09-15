<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
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
