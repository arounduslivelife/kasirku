<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Pengeluaran extends Model
{
    use HasFactory, LogsActivity;
    protected $table = 'pengeluaran';
    protected $fillable = [
        'kategori', 
        'nominal', 
        'keterangan', 
        'tanggal', 
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
