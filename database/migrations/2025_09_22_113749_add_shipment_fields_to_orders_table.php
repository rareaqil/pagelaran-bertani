<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // --- Kolom tambahan untuk pengiriman ---
            $table->timestamp('scheduled_at')->nullable()->after('expires_at');      // tanggal & jam kirim
            $table->integer('estimate_minutes')->nullable()->after('scheduled_at');   // durasi estimasi (menit)
            $table->string('courier')->nullable()->after('estimate_minutes');         // nama kurir
            $table->string('tracking_link')->nullable()->after('courier');            // link lacak
            $table->timestamp('estimated_arrival')->nullable()->after('tracking_link'); // waktu tiba (opsional)
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'scheduled_at',
                'estimate_minutes',
                'courier',
                'tracking_link',
                'estimated_arrival',
            ]);
        });
    }
};
