<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class ShiftController extends Controller
{
    public function openForm()
    {
        // Check if there is an active shift for this user
        $activeShift = Shift::where('user_id', Auth::id())->where('status', 'open')->first();
        if ($activeShift) {
            return redirect()->route('pos.index');
        }

        return view('shift.open');
    }

    public function open(Request $request)
    {
        $request->validate([
            'saldo_awal' => 'required|numeric|min:0',
        ]);

        Shift::create([
            'user_id' => Auth::id(),
            'saldo_awal' => $request->saldo_awal,
            'status' => 'open',
            'opened_at' => now(),
        ]);

        return redirect()->route('pos.index')->with('success', 'Shift kasir berhasil dibuka.');
    }

    public function closeForm()
    {
        $activeShift = Shift::where('user_id', Auth::id())->where('status', 'open')->first();
        if (!$activeShift) {
            return redirect()->route('pos.index')->with('error', 'Tidak ada shift aktif yang perlu ditutup.');
        }

        // Calculate expected cash
        $totalTunai = Penjualan::where('shift_id', $activeShift->id)
            ->where('metode_bayar', 'Tunai')
            ->sum('bayar');
        
        $totalKembali = Penjualan::where('shift_id', $activeShift->id)
            ->where('metode_bayar', 'Tunai')
            ->sum('kembali');

        $tunaiDiharapkan = $activeShift->saldo_awal + ($totalTunai - $totalKembali);

        return view('shift.close', compact('activeShift', 'tunaiDiharapkan'));
    }

    public function close(Request $request)
    {
        $request->validate([
            'tunai_fisik' => 'required|numeric|min:0',
        ]);

        $activeShift = Shift::where('user_id', Auth::id())->where('status', 'open')->first();
        if (!$activeShift) {
            return redirect()->route('pos.index')->with('error', 'Shift tidak ditemukan.');
        }

        // Re-calculate expected
        $totalTunai = Penjualan::where('shift_id', $activeShift->id)
            ->where('metode_bayar', 'Tunai')
            ->sum('bayar');
        
        $totalKembali = Penjualan::where('shift_id', $activeShift->id)
            ->where('metode_bayar', 'Tunai')
            ->sum('kembali');

        $tunaiDiharapkan = $activeShift->saldo_awal + ($totalTunai - $totalKembali);
        $selisih = $request->tunai_fisik - $tunaiDiharapkan;

        $activeShift->update([
            'tunai_fisik' => $request->tunai_fisik,
            'tunai_diharapkan' => $tunaiDiharapkan,
            'selisih' => $selisih,
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Shift kasir berhasil ditutup. Selisih: Rp ' . number_format($selisih, 0, ',', '.'));
    }
}
