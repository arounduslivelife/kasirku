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
        Schema::table('penjualan', function (Blueprint $table) {
            $table->enum('status_bayar', ['Lunas', 'Piutang'])->default('Lunas')->after('kembali');
            $table->decimal('sisa_piutang', 15, 2)->default(0)->after('status_bayar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->dropColumn(['status_bayar', 'sisa_piutang']);
        });
    }
};
