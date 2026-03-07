<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $configFile = 'db_config.json';
        if (\Illuminate\Support\Facades\Storage::exists($configFile)) {
            $config = json_decode(\Illuminate\Support\Facades\Storage::get($configFile), true);
            
            if ($config) {
                config([
                    'database.connections.mysql.host' => $config['host'] ?? config('database.connections.mysql.host'),
                    'database.connections.mysql.database' => $config['database'] ?? config('database.connections.mysql.database'),
                    'database.connections.mysql.username' => $config['username'] ?? config('database.connections.mysql.username'),
                    'database.connections.mysql.password' => $config['password'] ?? config('database.connections.mysql.password'),
                ]);
            }
        }
    }
}
