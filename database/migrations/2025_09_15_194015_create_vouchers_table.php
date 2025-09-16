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
       Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // kode voucher, misal "DISKON50"
            $table->enum('type', ['percentage', 'fixed']); // tipe: % atau nominal
            $table->decimal('value', 10, 2); // nilai diskon
            $table->decimal('min_order_amount', 12, 2)->default(0); // minimal belanja

            $table->integer('max_usage')->nullable(); // total bisa dipakai berapa kali
            $table->integer('used_count')->default(0); // sudah dipakai berapa kali

            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();

            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['code', 'is_active']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
