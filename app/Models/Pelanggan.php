<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Pelanggan extends Model
{
    use HasFactory, LogsActivity;
    protected $table = 'pelanggan';
    protected $fillable = ['nama', 'telepon', 'alamat'];
}
