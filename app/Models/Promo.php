<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable = [
        'nama', 'produk_id', 'tipe', 'nilai', 
        'min_beli', 'start_date', 'end_date', 'is_active'
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
