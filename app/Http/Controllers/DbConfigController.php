<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Exception;

class DbConfigController extends Controller
{
    protected $configFile = 'db_config.json';

    public function index()
    {
        $config = [];
        if (Storage::exists($this->configFile)) {
            $config = json_decode(Storage::get($this->configFile), true) ?: [];
        }

        return view('auth.config-db', compact('config'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'host' => 'required',
            'database' => 'required',
            'username' => 'required',
            'password' => 'nullable',
        ]);

        $config = $request->only(['host', 'database', 'username', 'password']);

        // Test connection before saving
        try {
            // Using a temporary connection name
            config(['database.connections.temp_test' => [
                'driver' => 'mysql',
                'host' => $config['host'],
                'database' => $config['database'],
                'username' => $config['username'],
                'password' => $config['password'],
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
            ]]);

            DB::connection('temp_test')->getPdo();
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Koneksi Gagal: ' . $e->getMessage());
        }

        // Save if successful
        Storage::put($this->configFile, json_encode($config));

        return redirect()->route('login')->with('success', 'Konfigurasi database berhasil disimpan.');
    }
}
