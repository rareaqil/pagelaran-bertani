<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    /**
     * Guarded attributes (tidak boleh di-mass-assign).
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Fillable attributes (boleh di-mass-assign).
     */
    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        'voucher_id',
        'discount_amount',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Voucher
    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    // Relasi ke OrderItem
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relasi ke Payment
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
