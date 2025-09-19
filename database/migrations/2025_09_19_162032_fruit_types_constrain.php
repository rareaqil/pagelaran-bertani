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
       Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('fruit_type_id')->nullable()->constrained('fruit_types')->nullOnDelete();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('fruit_type_id')->nullable()->constrained('fruit_types')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['fruit_type_id']);
            $table->dropColumn('fruit_type_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['fruit_type_id']);
            $table->dropColumn('fruit_type_id');
        });
    }
};
