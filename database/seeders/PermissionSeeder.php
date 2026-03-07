<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Master Data
            ['name' => 'view-produk', 'display_name' => 'Lihat Produk', 'module' => 'Master Data'],
            ['name' => 'manage-produk', 'display_name' => 'Kelola Produk (Tambah/Edit/Hapus)', 'module' => 'Master Data'],
            ['name' => 'view-pelanggan', 'display_name' => 'Lihat Pelanggan', 'module' => 'Master Data'],
            ['name' => 'manage-pelanggan', 'display_name' => 'Kelola Pelanggan', 'module' => 'Master Data'],
            ['name' => 'view-pemasok', 'display_name' => 'Lihat Pemasok', 'module' => 'Master Data'],
            ['name' => 'manage-pemasok', 'display_name' => 'Kelola Pemasok', 'module' => 'Master Data'],

            // Transaksi
            ['name' => 'view-invin', 'display_name' => 'Lihat Stok Masuk', 'module' => 'Transaksi Stok'],
            ['name' => 'manage-invin', 'display_name' => 'Catat Stok Masuk', 'module' => 'Transaksi Stok'],
            ['name' => 'view-invout', 'display_name' => 'Lihat Stok Keluar', 'module' => 'Transaksi Stok'],
            ['name' => 'manage-invout', 'display_name' => 'Catat Stok Keluar', 'module' => 'Transaksi Stok'],

            // POS
            ['name' => 'manage-pos', 'display_name' => 'Buka Kasir (POS)', 'module' => 'POS'],
            ['name' => 'view-pos-history', 'display_name' => 'Lihat Riwayat Penjualan', 'module' => 'POS'],

            // Laporan & Pengaturan
            ['name' => 'view-activity-log', 'display_name' => 'Lihat Log Aktivitas', 'module' => 'Laporan'],
            ['name' => 'manage-users', 'display_name' => 'Manajemen Pengguna', 'module' => 'Pengaturan'],
            ['name' => 'manage-settings', 'display_name' => 'Pengaturan HPP', 'module' => 'Pengaturan'],
            ['name' => 'manage-permissions', 'display_name' => 'Pengaturan Otoritas', 'module' => 'Pengaturan'],
        ];

        foreach ($permissions as $p) {
            $permission = Permission::updateOrCreate(['name' => $p['name']], $p);

            // Default Assignment
            // Owner gets everything
            DB::table('permission_role')->insertOrIgnore([
                'permission_id' => $permission->id,
                'role_name' => 'owner',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Admin gets most things
            if (!in_array($p['name'], ['manage-permissions'])) {
                DB::table('permission_role')->insertOrIgnore([
                    'permission_id' => $permission->id,
                    'role_name' => 'admin',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Kasir gets basic transactional access + POS
            if (in_array($p['name'], ['view-produk', 'view-pelanggan', 'view-pemasok', 'manage-invin', 'manage-invout', 'view-invin', 'view-invout', 'manage-pos', 'view-pos-history'])) {
                DB::table('permission_role')->insertOrIgnore([
                    'permission_id' => $permission->id,
                    'role_name' => 'kasir',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
