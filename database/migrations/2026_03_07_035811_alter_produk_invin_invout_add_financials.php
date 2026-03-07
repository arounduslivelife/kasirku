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
        Schema::table('produk', function (Blueprint $table) {
            $table->decimal('harga_beli', 15, 2)->default(0)->after('jumlah');
            $table->decimal('harga_jual', 15, 2)->default(0)->after('harga_beli');
        });

        Schema::table('invin', function (Blueprint $table) {
            $table->decimal('harga_beli', 15, 2)->default(0)->after('qty');
            $table->decimal('qty_sisa', 10, 2)->default(0)->after('harga_beli')->comment('Sisa qty untuk antrean FIFO');
        });

        Schema::table('invout', function (Blueprint $table) {
            $table->decimal('harga_jual', 15, 2)->default(0)->after('qty');
            $table->decimal('hpp', 15, 2)->default(0)->after('harga_jual')->comment('Harga Pokok Penjualan saat rilis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropColumn(['harga_beli', 'harga_jual']);
        });

        Schema::table('invin', function (Blueprint $table) {
            $table->dropColumn(['harga_beli', 'qty_sisa']);
        });

        Schema::table('invout', function (Blueprint $table) {
            $table->dropColumn(['harga_jual', 'hpp']);
        });
    }
};
