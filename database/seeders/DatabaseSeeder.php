<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Toko Assco Owner',
            'email' => 'owner@assco.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);

        User::factory()->create([
            'name' => 'Toko Assco Admin',
            'email' => 'admin@assco.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Kasir 1',
            'email' => 'kasir@assco.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
        ]);

        // Default setting for HPP
        \App\Models\Setting::create([
            'key' => 'hpp_method',
            'value' => 'AVERAGE',
        ]);
    }
}
