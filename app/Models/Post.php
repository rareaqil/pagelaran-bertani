<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi mass-assignment.
     * Sesuaikan dengan kolom di tabel posts.
     */
    protected $fillable = [
        'title',
        'slug',
        'body',
        'image',      // misal untuk menyimpan path gambar
        'user_id',    // relasi ke user pembuat post
        'published_at'
    ];

    /**
     * Casting kolom ke tipe tertentu.
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Relasi: Post dimiliki oleh User (penulis).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * (Opsional) Relasi: Post punya banyak komentar.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * (Opsional) Relasi: Kategori.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
