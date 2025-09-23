<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;


class StockMovement extends Model
{
    use HasFactory,SoftDeletes;

     protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'reference_type',
        'reference_id',
        'related_movement_id',
    ];

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    // Relasi ke Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Bisa jadi relasi polymorphic ke reference (order, restock, dll)
    public function reference()
    {
        Log::info('MorphTo reference', [
            'reference_type' => $this->reference_type,
            'reference_id'   => $this->reference_id,
        ]);

        // Pakai order_id sebagai owner key jika memang reference_id menyimpan nilai order_id
        return $this->morphTo(__FUNCTION__, 'reference_type', 'reference_id', 'order_id');
    }


     public function relatedMovement()
    {
        return $this->belongsTo(StockMovement::class, 'related_movement_id'); // sesuaikan nama field
    }


}
