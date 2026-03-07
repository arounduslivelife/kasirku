<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Invout extends Model
{
    use HasFactory, LogsActivity;
    protected $table = 'invout';
    protected $fillable = ['produk_id', 'pelanggan_id', 'tanggal', 'qty', 'harga_jual', 'hpp', 'keterangan'];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }
}
