<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetodePembayaran extends Model
{
    protected $table = 'metode_pembayarans';
    protected $fillable = ['nama', 'kode', 'logo', 'deskripsi', 'status'];
    protected $casts = [
        'status' => 'boolean',
    ];

}
