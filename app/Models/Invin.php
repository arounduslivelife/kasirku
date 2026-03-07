<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Invin extends Model
{
    use HasFactory, LogsActivity;
    protected $table = 'invin';
    protected $fillable = ['produk_id', 'pemasok_id', 'tanggal', 'qty', 'harga_beli', 'qty_sisa', 'keterangan'];

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function pemasok()
    {
        return $this->belongsTo(Pemasok::class, 'pemasok_id');
    }
}
