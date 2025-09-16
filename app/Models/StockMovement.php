<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        return $this->morphTo(__FUNCTION__, 'reference_type', 'reference_id');
    }

     public function relatedMovement()
    {
        return $this->belongsTo(StockMovement::class, 'related_movement_id'); // sesuaikan nama field
    }


}
