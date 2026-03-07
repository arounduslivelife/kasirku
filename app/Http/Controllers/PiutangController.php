<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\PiutangPembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PiutangController extends Controller
{
    public function index()
    {
        $piutangs = Penjualan::with('pelanggan')
            ->where('status_bayar', 'Piutang')
            ->orderBy('tanggal', 'desc')
            ->paginate(10);
            
        return view('piutang.index', compact('piutangs'));
    }

    public function show($id)
    {
        $penjualan = Penjualan::with(['pelanggan', 'details.produk', 'user'])->findOrFail($id);
        $pembayarans = PiutangPembayaran::where('penjualan_id', $id)->orderBy('tanggal', 'desc')->get();
        
        return view('piutang.show', compact('penjualan', 'pembayarans'));
    }

    public function bayar(Request $request, $id)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:1',
            'tanggal' => 'required|date',
            'keterangan' => 'nullable|string'
        ]);

        $penjualan = Penjualan::findOrFail($id);

        if ($request->nominal > $penjualan->sisa_piutang) {
            return back()->with('error', 'Nominal bayar melebihi sisa piutang.');
        }

        DB::transaction(function () use ($request, $penjualan) {
            PiutangPembayaran::create([
                'penjualan_id' => $penjualan->id,
                'nominal' => $request->nominal,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan
            ]);

            $penjualan->sisa_piutang -= $request->nominal;
            
            if ($penjualan->sisa_piutang <= 0) {
                $penjualan->status_bayar = 'Lunas';
                $penjualan->sisa_piutang = 0;
            }

            $penjualan->save();
        });

        return redirect()->route('piutang.show', $id)->with('success', 'Pembayaran cicilan berhasil dicatat.');
    }
}
