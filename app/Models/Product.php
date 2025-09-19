<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Binafy\LaravelCart\Cartable;

class Product extends Model implements Cartable
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'fruit_type_id',
        'stock',
        'status_active',
        'image',
        'weight',
        'sku',
    ];

    /*
    |--------------------------------------------------------------------------
    | Cartable (wajib karena pakai binafy/laravel-cart)
    |--------------------------------------------------------------------------
    */
    public function getPrice(): float
    {
        return (float) $this->price;
    }

    public function getAvailableStockAttribute()
    {
        $holdQty = $this->stockMovements()
            ->where('type', 'hold')
            ->sum('quantity');

        return $this->stock - $holdQty;
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */
    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }

    public function activeTestimonials()
    {
        return $this->hasMany(Testimonial::class)->where('is_approved', true);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function fruitType()
    {
        return $this->belongsTo(FruitType::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
