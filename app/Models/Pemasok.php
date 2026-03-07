<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Pemasok extends Model
{
    use HasFactory, LogsActivity;
    protected $table = 'pemasok';
    protected $fillable = ['nama', 'telepon', 'alamat'];
}
