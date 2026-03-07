<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invin;
use App\Models\Produk;

class InvinController extends Controller
{
    public function index()
    {
        $invins = Invin::with('produk')->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc')->get();
        return view('invin.index', compact('invins'));
    }

    public function create()
    {
        $produks = Produk::orderBy('nama', 'asc')->get();
        $pemasoks = \App\Models\Pemasok::orderBy('nama', 'asc')->get();
        return view('invin.create', compact('produks', 'pemasoks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pemasok_id' => 'nullable|exists:pemasok,id',
            'tanggal' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.qty' => 'required|numeric|min:0.01',
            'items.*.harga_beli' => 'required|numeric|min:0',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            $hppService = new \App\Services\HppService();

            foreach ($request->items as $item) {
                // Catat stok masuk
                $invin = Invin::create([
                    'produk_id' => $item['produk_id'],
                    'pemasok_id' => $request->pemasok_id,
                    'tanggal' => $request->tanggal,
                    'qty' => $item['qty'],
                    'qty_sisa' => $item['qty'], // Untuk FIFO
                    'harga_beli' => $item['harga_beli'],
                    'keterangan' => 'Batch Entry: ' . ($request->keterangan ?? '-')
                ]);

                // Update stok master produk
                $produk = Produk::find($item['produk_id']);
                
                // Kalkulasi & Update Rata-Rata Harga Beli
                $hppService->updateHargaBeliAverage($produk, $item['qty'], $item['harga_beli']);

                $produk->jumlah += $item['qty'];
                $produk->save();
            }
        });

        return redirect()->route('invin.index')->with('success', count($request->items) . ' transaksi stok masuk berhasil dicatat secara kolektif.');
    }

    public function destroy($id)
    {
        $invin = Invin::findOrFail($id);
        
        // Kembalikan (kurangi) stok utama
        $produk = Produk::find($invin->produk_id);
        if ($produk) {
            $produk->jumlah -= $invin->qty;
            $produk->save();
        }

        $invin->delete();
        
        return redirect()->route('invin.index')->with('success', 'Riwayat Stok Masuk dihapus, kuantiti pada master produk disesuaikan.');
    }
}
