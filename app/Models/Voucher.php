<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{


    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    // Relasi ke Order (jika 1 order bisa pakai 1 voucher)
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    protected $casts = [
    'start_date' => 'datetime',
    'end_date'   => 'datetime',
    'is_active'  => 'boolean',
    ];

    // Cek apakah voucher masih valid
    public function isValid($orderAmount = 0): bool
    {
        $now = now();

        if (!$this->is_active) return false;

        if ($this->start_date && $now->lt($this->start_date)) return false;
        if ($this->end_date && $now->gt($this->end_date)) return false;

        if (!is_null($this->max_usage) && $this->used_count >= $this->max_usage) return false;

        if ($orderAmount < $this->min_order_amount) return false;

        return true;
    }

    // Hitung potongan harga
    public function getDiscount($orderAmount): float
    {
        if (!$this->isValid($orderAmount)) return 0;

        if ($this->type === 'percentage') {
            // dibulatkan ke bilangan bulat terdekat
            return round($orderAmount * ($this->value / 100));
        }

        // type = fixed → batasi maksimal 500
        return round(min($this->value, max(0, $orderAmount - 500)));
    }

    public function getDiscountOnly($orderAmount): float
    {
        if ($this->type === 'percentage') {
            return round($orderAmount * ($this->value / 100));
        }

        return round(min($this->value, max(0, $orderAmount - 500)));
    }
}
