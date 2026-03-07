<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invout;
use App\Models\Produk;

class InvoutController extends Controller
{
    public function index()
    {
        $invouts = Invout::with('produk')->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc')->get();
        return view('invout.index', compact('invouts'));
    }

    public function create()
    {
        $produks = Produk::orderBy('nama', 'asc')->get();
        $pelanggans = \App\Models\Pelanggan::orderBy('nama', 'asc')->get();
        return view('invout.create', compact('produks', 'pelanggans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'nullable|exists:pelanggan,id',
            'tanggal' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.harga_jual' => 'required|numeric|min:0',
        ]);

        return \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            $hppService = new \App\Services\HppService();
            $totalItems = 0;

            foreach ($request->items as $item) {
                $produk = Produk::find($item['produk_id']);

                // Validasi stok tidak boleh minus
                if ($produk->jumlah < $item['qty']) {
                    throw new \Exception('Stok ' . $produk->nama . ' tidak mencukupi. Sisa: ' . $produk->jumlah);
                }

                // Hitung HPP melalui Service (FIFO)
                $totalHpp = $hppService->hitungHppKeluar($produk, $item['qty']);

                // Catat stok keluar
                Invout::create([
                    'produk_id' => $item['produk_id'],
                    'pelanggan_id' => $request->pelanggan_id,
                    'tanggal' => $request->tanggal,
                    'qty' => $item['qty'],
                    'harga_jual' => $item['harga_jual'],
                    'hpp' => $totalHpp,
                    'keterangan' => 'Batch Exit: ' . ($request->keterangan ?? '-')
                ]);

                // Kurangi stok master produk
                $produk->jumlah -= $item['qty'];
                $produk->save();
                
                $totalItems++;
            }

            return redirect()->route('invout.index')->with('success', $totalItems . ' transaksi stok keluar berhasil dicatat secara kolektif.');
        });
    }

    public function destroy($id)
    {
        $invout = Invout::findOrFail($id);
        
        // Kembalikan (tambah) stok utama
        $produk = Produk::find($invout->produk_id);
        if ($produk) {
            $produk->jumlah += $invout->qty;
            $produk->save();
        }

        $invout->delete();
        
        return redirect()->route('invout.index')->with('success', 'Riwayat Stok Keluar dihapus, kuantiti pada master produk disesuaikan.');
    }
}
