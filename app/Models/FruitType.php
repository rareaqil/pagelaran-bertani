<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FruitType extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'slug'];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
