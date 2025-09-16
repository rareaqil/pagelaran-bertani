<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    // Relasi ke Order (jika 1 order bisa pakai 1 voucher)
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

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

        return $this->type === 'percentage'
            ? $orderAmount * ($this->value / 100)
            : min($this->value, $orderAmount);
    }
}
