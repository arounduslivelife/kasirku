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
        $produks = Produk::with('satuans')->where('jumlah', '>', 0)->get();
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
            
            $activeShift = \App\Models\Shift::where('user_id', Auth::id())->where('status', 'open')->first();

            $penjualan = Penjualan::create([
                'nomor_invoice' => $invoiceNum,
                'user_id' => Auth::id(),
                'shift_id' => $activeShift->id,
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

                $qty_konversi = $item['qty'];
                $produk_satuan_id = $item['produk_satuan_id'] ?? null;
                $harga_jual = $produk->harga_jual;

                if ($produk_satuan_id) {
                    $satuan = \App\Models\ProdukSatuan::find($produk_satuan_id);
                    if ($satuan) {
                        $qty_konversi = $item['qty'] * $satuan->konversi;
                        $harga_jual = $satuan->harga_jual;
                    }
                }

                if ($produk->jumlah < $qty_konversi) {
                    throw new \Exception('Stok ' . $produk->nama . ' tidak mencukupi.');
                }

                $subtotalOriginal = $item['qty'] * $harga_jual;
                
                // Calculate promo (Promo logic might need adjustment for multi-unit, for now we apply to line total)
                $promoData = (new \App\Services\PromoService())->calculateDiscount($produk, $item['qty']);
                $diskonPerItem = $promoData['diskon_per_item'] ?? 0;
                $totalDiskonItem = $diskonPerItem * $item['qty'];
                $subtotal = $subtotalOriginal - $totalDiskonItem;
                
                $totalHpp = $hppService->hitungHppKeluar($produk, $qty_konversi);

                PenjualanDetail::create([
                    'penjualan_id' => $penjualan->id,
                    'produk_id' => $produk->id,
                    'produk_satuan_id' => $produk_satuan_id,
                    'qty' => $item['qty'],
                    'harga_jual' => $harga_jual,
                    'diskon_produk' => $totalDiskonItem,
                    'promo_id' => $promoData['promo_id'],
                    'hpp' => $totalHpp,
                    'subtotal' => $subtotal
                ]);

                // Record to invout for backwards compatibility or integrated reporting if needed
                // For now, we use PenjualanDetail as the primary source for POS sales.
                
                $produk->jumlah -= $qty_konversi;
                $produk->save();

                $totalHarga += $subtotal;
                $totalItem += $item['qty'];
            }

            $penjualan->update([
                'total_harga' => $totalHarga,
                'total_item' => $totalItem,
                'kembali' => $request->bayar - $totalHarga > 0 ? $request->bayar - $totalHarga : 0,
                'status_bayar' => $request->bayar < $totalHarga ? 'Piutang' : 'Lunas',
                'sisa_piutang' => $request->bayar < $totalHarga ? ($totalHarga - $request->bayar) : 0
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
