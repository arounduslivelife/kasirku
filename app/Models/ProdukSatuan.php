<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukSatuan extends Model
{
    protected $table = 'produk_satuan';
    
    protected $fillable = [
        'produk_id', 'nama_satuan', 'konversi', 'harga_jual'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
