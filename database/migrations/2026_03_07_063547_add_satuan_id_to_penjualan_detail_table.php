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
            $table->foreignId('produk_satuan_id')->nullable()->constrained('produk_satuan')->onDelete('set null')->after('produk_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjualan_detail', function (Blueprint $table) {
            $table->dropForeign(['produk_satuan_id']);
            $table->dropColumn('produk_satuan_id');
        });
    }
};
