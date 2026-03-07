<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Pelanggan;
use App\Services\HppService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PosController extends Controller
{
    public function index()
    {
        $produks = Produk::where('jumlah', '>', 0)->get();
        $pelanggans = Pelanggan::all();
        return view('pos.index', compact('produks', 'pelanggans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'nullable|exists:pelanggan,id',
            'metode_bayar' => 'required',
            'bayar' => 'required|numeric',
            'items' => 'required|array|min:1',
            'items.*.produk_id' => 'required|exists:produk,id',
            'items.*.qty' => 'required|numeric|min:0.01',
        ]);

        return DB::transaction(function () use ($request) {
            $hppService = new HppService();
            
            // Generate Invoice Number: INV/YYYY/MM/DD/RAND
            $invoiceNum = 'INV/' . date('Ymd') . '/' . strtoupper(bin2hex(random_bytes(3)));

            $penjualan = Penjualan::create([
                'nomor_invoice' => $invoiceNum,
                'user_id' => Auth::id(),
                'pelanggan_id' => $request->pelanggan_id,
                'metode_bayar' => $request->metode_bayar,
                'bayar' => $request->bayar,
                'tanggal' => date('Y-m-d'),
                'total_harga' => 0, // Will be updated
                'total_item' => 0,  // Will be updated
                'kembali' => 0      // Will be updated
            ]);

            $totalHarga = 0;
            $totalItem = 0;

            foreach ($request->items as $item) {
                $produk = Produk::lockForUpdate()->find($item['produk_id']);

                if ($produk->jumlah < $item['qty']) {
                    throw new \Exception('Stok ' . $produk->nama . ' tidak mencukupi.');
                }

                $subtotal = $item['qty'] * $produk->harga_jual;
                $totalHpp = $hppService->hitungHppKeluar($produk, $item['qty']);

                PenjualanDetail::create([
                    'penjualan_id' => $penjualan->id,
                    'produk_id' => $produk->id,
                    'qty' => $item['qty'],
                    'harga_jual' => $produk->harga_jual,
                    'hpp' => $totalHpp,
                    'subtotal' => $subtotal
                ]);

                // Record to invout for backwards compatibility or integrated reporting if needed
                // For now, we use PenjualanDetail as the primary source for POS sales.
                
                $produk->jumlah -= $item['qty'];
                $produk->save();

                $totalHarga += $subtotal;
                $totalItem += $item['qty'];
            }

            $penjualan->update([
                'total_harga' => $totalHarga,
                'total_item' => $totalItem,
                'kembali' => $request->bayar - $totalHarga
            ]);

            return response()->json([
                'status' => 'success',
                'invoice' => $penjualan->nomor_invoice,
                'id' => $penjualan->id
            ]);
        });
    }

    public function showReceipt($id)
    {
        $penjualan = Penjualan::with(['details.produk', 'user', 'pelanggan'])->findOrFail($id);
        return view('pos.receipt', compact('penjualan'));
    }

    public function history()
    {
        $sales = Penjualan::with(['user', 'pelanggan'])->latest()->paginate(10);
        return view('pos.history', compact('sales'));
    }
}
