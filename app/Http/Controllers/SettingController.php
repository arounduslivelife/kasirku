<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $hppSetting = Setting::where('key', 'hpp_method')->first();
        $hppMethod = $hppSetting ? $hppSetting->value : 'AVERAGE';
        
        return view('settings.index', compact('hppMethod'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'hpp_method' => 'required|in:FIFO,AVERAGE'
        ]);

        $setting = Setting::firstOrCreate(['key' => 'hpp_method']);
        $setting->value = $request->hpp_method;
        $setting->save();

        return redirect()->route('settings.index')->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
