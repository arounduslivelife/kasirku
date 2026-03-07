<?php

namespace App\Services;

use App\Models\Promo;
use App\Models\Produk;

class PromoService
{
    /**
     * Calculate discount for a product based on active promos.
     */
    public function calculateDiscount(Produk $produk, $qty)
    {
        $today = date('Y-m-d');
        
        $promo = Promo::where('produk_id', $produk->id)
            ->where('is_active', true)
            ->where('min_beli', '<=', $qty)
            ->where(function ($query) use ($today) {
                $query->whereNull('start_date')->orWhere('start_date', '<=', $today);
            })
            ->where(function ($query) use ($today) {
                $query->whereNull('end_date')->orWhere('end_date', '>=', $today);
            })
            ->orderBy('min_beli', 'desc') // Best discount for higher quantity
            ->first();

        if (!$promo) {
            return [
                'diskon_per_item' => 0,
                'promo_id' => null,
                'nama_promo' => null
            ];
        }

        $diskon = 0;
        if ($promo->tipe === 'persen') {
            $diskon = ($promo->nilai / 100) * $produk->harga_jual;
        } elseif ($promo->tipe === 'potongan_tetap') {
            $diskon = $promo->nilai;
        } elseif ($promo->tipe === 'grosir') {
            // In wholesale, 'nilai' is usually the fixed discounted price per item
            // Here we assume 'nilai' is the AMOUNT to be deducted per item
            $diskon = $promo->nilai;
        }

        return [
            'diskon_per_item' => $diskon,
            'promo_id' => $promo->id,
            'nama_promo' => $promo->nama
        ];
    }
}
