<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'payment_gateway',
        'transaction_id',
        'status',
        'amount',
        'paid_at',
        'snap_token',
        'raw_response',
    ];

    protected $guarded = ['id', 'created_at', 'updated_at'];

     protected $casts = [
        'raw_response' => 'array', // otomatis encode/decode JSON
    ];

    // Relasi ke Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
