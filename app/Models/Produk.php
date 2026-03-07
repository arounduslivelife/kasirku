<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Produk extends Model
{
    use HasFactory, LogsActivity;
    protected $table = 'produk';
    public $timestamps = false;
    protected $fillable = ['kode', 'nama', 'jumlah', 'harga_beli', 'harga_jual', 'satuan', 'keterangan'];

    public function invin()
    {
        return $this->hasMany(Invin::class, 'produk_id');
    }

    public function invout()
    {
        return $this->hasMany(Invout::class, 'produk_id');
    }

    public function satuans()
    {
        return $this->hasMany(ProdukSatuan::class, 'produk_id');
    }
}
