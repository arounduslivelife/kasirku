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
        Schema::table('penjualan_detail', function (Blueprint $table) {
            $table->decimal('diskon_produk', 15, 2)->default(0)->after('harga_jual');
            $table->foreignId('promo_id')->nullable()->constrained('promos')->onDelete('set null')->after('diskon_produk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjualan_detail', function (Blueprint $table) {
            $table->dropForeign(['promo_id']);
            $table->dropColumn(['diskon_produk', 'promo_id']);
        });
    }
};
