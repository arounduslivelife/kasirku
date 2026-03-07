<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class DbConfigMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for the config page itself or storage paths
        if ($request->is('config-db*') || $request->is('storage*')) {
            return $next($request);
        }

        $configFile = 'db_config.json';

        if (!Storage::exists($configFile)) {
            return redirect()->route('db.config.index')->with('warning', 'Silakan konfigurasi database terlebih dahulu.');
        }

        try {
            // Check if connection is working
            DB::connection()->getPdo();
        } catch (Exception $e) {
            // If connection fails, redirect to config page
            return redirect()->route('db.config.index')->with('error', 'Gagal terhubung ke database. Silakan periksa konfigurasi Anda.');
        }

        return $next($request);
    }
}
