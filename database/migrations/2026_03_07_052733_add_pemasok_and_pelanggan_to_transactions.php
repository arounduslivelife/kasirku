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
        Schema::table('invin', function (Blueprint $table) {
            $table->unsignedBigInteger('pemasok_id')->nullable()->after('produk_id');
            $table->foreign('pemasok_id')->references('id')->on('pemasok')->onDelete('set null');
        });

        Schema::table('invout', function (Blueprint $table) {
            $table->unsignedBigInteger('pelanggan_id')->nullable()->after('produk_id');
            $table->foreign('pelanggan_id')->references('id')->on('pelanggan')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invin', function (Blueprint $table) {
            $table->dropForeign(['pemasok_id']);
            $table->dropColumn('pemasok_id');
        });

        Schema::table('invout', function (Blueprint $table) {
            $table->dropForeign(['pelanggan_id']);
            $table->dropColumn('pelanggan_id');
        });
    }
};
