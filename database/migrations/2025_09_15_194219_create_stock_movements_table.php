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
       Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->enum('type', ['in', 'out', 'hold', 'reversal']);
            $table->integer('quantity');
            $table->string('reference_type')->nullable(); // misal 'order'
            $table->unsignedBigInteger('reference_id')->nullable(); // id dari order
            $table->unsignedBigInteger('related_movement_id')->nullable();
            $table->timestamps();

            $table->softDeletes();
            $table->index(['product_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
