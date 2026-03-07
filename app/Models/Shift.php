<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'user_id', 'saldo_awal', 'saldo_akhir', 
        'tunai_diharapkan', 'tunai_fisik', 'selisih', 
        'status', 'opened_at', 'closed_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function penjualans()
    {
        return $this->hasMany(Penjualan::class);
    }
}
