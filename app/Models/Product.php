<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Binafy\LaravelCart\Cartable;

class Product extends Model implements Cartable
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'price',
        'stock',
        'is_active',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
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

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
