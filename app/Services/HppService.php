<?php

namespace App\Services;

use App\Models\Produk;
use App\Models\Invin;
use App\Models\Setting;

class HppService
{
    /**
     * Menghitung HPP (Harga Pokok Penjualan) total untuk suatu produk saat dijual
     * 
     * @param Produk $produk   Produk yang dijual
     * @param float $qtyKeluar Jumlah barang yang dijual
     * @return float           Total HPP untuk kuantitas yang dijual
     */
    public function hitungHppKeluar(Produk $produk, float $qtyKeluar): float
    {
        $setting = Setting::where('key', 'hpp_method')->first();
        $method = $setting ? strtoupper($setting->value) : 'AVERAGE';

        if ($method === 'FIFO') {
            return $this->hitungFifo($produk, $qtyKeluar);
        }

        // Default: AVERAGE
        return $this->hitungAverage($produk, $qtyKeluar);
    }

    /**
     * Hitung HPP dengan metode FIFO
     * Mengurangi antrean qty_sisa dari stok masuk yang paling lama
     */
    private function hitungFifo(Produk $produk, float $qtyKeluar): float
    {
        $totalHpp = 0;
        $qtySisaDibutuhkan = $qtyKeluar;

        // Ambil riwayat stok masuk yang masih punya sisa qty, urutkan dari yang paling tua (FIFO)
        $antreanStok = Invin::where('produk_id', $produk->id)
                            ->where('qty_sisa', '>', 0)
                            ->orderBy('created_at', 'asc')
                            ->get();

        foreach ($antreanStok as $stokMasuk) {
            if ($qtySisaDibutuhkan <= 0) break;

            if ($stokMasuk->qty_sisa >= $qtySisaDibutuhkan) {
                // Stok ini cukup untuk menutupi kebutuhan
                $totalHpp += ($qtySisaDibutuhkan * $stokMasuk->harga_beli);
                
                // Kurangi sisa antrean di database
                $stokMasuk->qty_sisa -= $qtySisaDibutuhkan;
                $stokMasuk->save();
                
                $qtySisaDibutuhkan = 0;
            } else {
                // Stok ini tidak cukup, habiskan stok ini dan lanjut ke batch berikutnya
                $totalHpp += ($stokMasuk->qty_sisa * $stokMasuk->harga_beli);
                $qtySisaDibutuhkan -= $stokMasuk->qty_sisa;
                
                $stokMasuk->qty_sisa = 0;
                $stokMasuk->save();
            }
        }

        // Catatan: Jika qtySisaDibutuhkan > 0 di akhir loop, artinya stok tercatat lebih kecil 
        // dari yang dijual (minus). Dalam kondisi ideal, ini sudah dihadang oleh validasi stok di Controller.
        // Jika lolos, ia akan menggunakan harga_beli master (fallback).
        if ($qtySisaDibutuhkan > 0) {
            $totalHpp += ($qtySisaDibutuhkan * $produk->harga_beli);
        }

        return $totalHpp;
    }

    /**
     * Hitung HPP dengan metode Rata-Rata (Moving Average)
     * Rata-rata sederhana: Karena kita asumsikan harga_beli di tabel Master Produk 
     * selalu diperbarui menjadi rata-rata terbaru setiap ada barang masuk,
     * HPP didapat dengan mengalikan qty keluar dengan harga_beli rata-rata saat ini.
     */
    private function hitungAverage(Produk $produk, float $qtyKeluar): float
    {
        return $qtyKeluar * $produk->harga_beli;
    }

    /**
     * Memperbarui Harga Beli Rata-Rata (Moving Average) pada Master Produk
     * Dipanggil setiap kali ada Transaksi Stok Masuk
     */
    public function updateHargaBeliAverage(Produk $produk, float $qtyMasukBaru, float $hargaBeliBaru): void
    {
        $setting = Setting::where('key', 'hpp_method')->first();
        $method = $setting ? strtoupper($setting->value) : 'AVERAGE';

        if ($method === 'FIFO') {
            // Pada metode FIFO, harga_beli di Master Produk bisa diisi dengan harga pembelian terakhir
            // sebagai indikator. Tidak mengubah hitungan FIFO yang sebenarnya didasarkan antrean.
            $produk->harga_beli = $hargaBeliBaru;
            $produk->save();
            return;
        }

        // Jika metode AVERAGE
        $qtyLama = $produk->jumlah;        // Jumlah sisa sebelum barang masuk BUKAN 0
        $hargaBeliLama = $produk->harga_beli;

        // Total nilai uang lama + total uang nilai baru
        $totalNilaiLama = $qtyLama * $hargaBeliLama;
        $totalNilaiBaru = $qtyMasukBaru * $hargaBeliBaru;
        $qtyTotal = $qtyLama + $qtyMasukBaru;

        if ($qtyTotal > 0) {
            $hargaAverageBaru = ($totalNilaiLama + $totalNilaiBaru) / $qtyTotal;
            $produk->harga_beli = $hargaAverageBaru;
            $produk->save();
        }
    }
}
