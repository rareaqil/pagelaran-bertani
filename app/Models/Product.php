<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Binafy\LaravelCart\Cartable;

class Product extends Model implements Cartable
{
    protected $fillable = ['name', 'price'];

    // Metode wajib dari Cartable
    public function getPrice(): float
    {
        return (float) $this->price;
    }
}
