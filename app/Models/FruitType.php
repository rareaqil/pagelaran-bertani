<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;;

class FruitType extends Model
{

    protected $fillable = ['name', 'slug','is_active'];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
