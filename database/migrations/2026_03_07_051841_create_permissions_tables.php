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
        Schema::create('permissions', function (Blueprint $column) {
            $column->id();
            $column->string('name')->unique(); // e.g. 'manage-users'
            $column->string('display_name');  // e.g. 'Manajemen Pengguna'
            $column->string('module');        // e.g. 'Sistem'
            $column->timestamps();
        });

        Schema::create('permission_role', function (Blueprint $column) {
            $column->id();
            $column->foreignId('permission_id')->constrained()->onDelete('cascade');
            $column->string('role_name'); // owner, admin, kasir
            $column->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
    }
};
