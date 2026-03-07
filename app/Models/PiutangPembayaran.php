<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PiutangPembayaran extends Model
{
    protected $table = 'piutang_pembayaran';
    
    protected $fillable = [
        'penjualan_id', 'nominal', 'tanggal', 'keterangan'
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }
}
