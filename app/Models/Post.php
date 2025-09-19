<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Kolom yang boleh diisi mass-assignment
     */
    protected $fillable = [
        'name',
        'slug',
        'intro',
        'content',      // Summernote HTML
        'type',
        'image',        // path LFM
        'status',
        'created_by',
        'fruit_type_id',
        'created_by_name',
        'updated_by',
        'deleted_by',
        'published_at',
    ];

    /**
     * Casting kolom ke tipe tertentu
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Relasi: Post dimiliki oleh User (pembuat)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi: User yang mengupdate post
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Relasi: User yang menghapus post
     */
    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Accessor opsional: tampilkan status dengan huruf kapital
     */
    public function getStatusLabelAttribute()
    {
        return ucfirst($this->status);
    }

    public function fruitType()
    {
        return $this->belongsTo(FruitType::class);
    }

    public function getRouteKeyName()
{
    return 'slug';
}

}
