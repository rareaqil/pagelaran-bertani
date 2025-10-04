<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

enum OrderStatus: string
{
    case Pending   = 'Pending';
    case Paid      = 'Paid';
    case Shipment  = 'Shipment';
    case Completed = 'Completed';
    case Cancelled = 'Cancelled';


     public function color(): string
    {
        return match($this) {
            self::Paid => 'text-green-600',
            self::Pending => 'text-yellow-600',
            self::Shipment => 'text-blue-600',
            self::Cancelled => 'text-red-600',
            self::Completed => 'text-gray-600',
        };
    }
}

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
        'admin_fee',

        // Shipment
        'scheduled_at',
        'estimate_minutes',
        'courier',
        'tracking_link',
        'estimated_arrival',
    ];

    protected $casts = [
        'scheduled_at'     => 'datetime',
        'estimated_arrival'=> 'datetime',
        'status' => OrderStatus::class,
    ];



     protected static function booted()
    {
        static::creating(function ($order) {
            // Generate order_id jika belum ada
            if (!$order->order_id) {
                $date = now()->format('Ymd'); // tanggal
                $userId = 'U' . $order->user_id; // user id
                // Hitung jumlah order hari ini untuk counter
                $todayCount = self::whereDate('created_at', now()->toDateString())->count() + 1;
                $counter = 'C' . str_pad($todayCount, 2, '0', STR_PAD_LEFT);
                // Random 4 karakter alphanumeric
                $random = Str::upper(Str::random(4));

                $order->order_id = "ORD{$date}{$userId}{$counter}{$random}";
            }
        });
    }

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


    public static function statuses(): array
    {
        return array_map(fn($status) => $status->value, OrderStatus::cases());
    }

    public function isStatus(OrderStatus $status): bool
    {
        return $this->status === $status->value;
    }


    public function getRouteKeyName()
    {
        return 'order_id';
    }
}
