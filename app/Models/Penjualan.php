<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Penjualan extends Model
{
    use HasFactory, LogsActivity;
    protected $table = 'penjualan';
    protected $fillable = [
        'nomor_invoice', 
        'user_id', 
        'pelanggan_id', 
        'total_item', 
        'total_harga', 
        'bayar', 
        'kembali', 
        'metode_bayar', 
        'tanggal'
    ];

    public function details()
    {
        return $this->hasMany(PenjualanDetail::class, 'penjualan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }
}
