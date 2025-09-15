<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');                          // nama produk
            $table->text('description')->nullable();         // deskripsi produk
            $table->decimal('price', 10, 2);                 // harga
            $table->integer('stock')->default(0);            // stok on-hand
            $table->boolean('status_active')->default(true);        // aktif / nonaktif
            $table->string('image')->nullable();             // gambar produk
            $table->decimal('weight', 8, 2)->nullable();     // berat produk (kg/gr)
            $table->string('sku')->nullable()->unique();     // kode unik produk
            $table->timestamps();
            $table->softDeletes(); 

            $table->index(['name', 'status']); // untuk pencarian & filter cepat
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
