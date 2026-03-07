<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Produk;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::all();
        return view('produk.index', compact('produks'));
    }

    public function create()
    {
        return view('produk.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'nullable|unique:produk,kode',
            'nama' => 'required',
            'satuan' => 'required',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'keterangan' => 'nullable'
        ]);

        $data = $request->all();
        $data['jumlah'] = 0; 

        $produk = Produk::create($data);

        // Handle Multi-Units (Satuan Bertingkat)
        if ($request->has('satuans')) {
            foreach ($request->satuans as $satuan) {
                if (!empty($satuan['nama_satuan']) && !empty($satuan['konversi'])) {
                    $produk->satuans()->create($satuan);
                }
            }
        }

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan. Stok dapat diisi melalui menu Stok Masuk.');
    }

    public function edit(Produk $produk)
    {
        $produk->load('satuans');
        return view('produk.edit', compact('produk'));
    }

    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'kode' => 'nullable|unique:produk,kode,' . $produk->id,
            'nama' => 'required',
            'satuan' => 'required',
            'harga_beli' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'keterangan' => 'nullable'
        ]);

        $produk->update($request->except('jumlah')); 
        
        // Handle Multi-Units (Satuan Bertingkat)
        if ($request->has('satuans')) {
            // Delete removed units if needed, or just sync. For simplicity, we refresh.
            $produk->satuans()->delete();
            foreach ($request->satuans as $satuan) {
                if (!empty($satuan['nama_satuan']) && !empty($satuan['konversi'])) {
                    $produk->satuans()->create($satuan);
                }
            }
        }

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Produk $produk)
    {
        $produk->delete();
        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus.');
    }
}
