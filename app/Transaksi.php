<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'id',
        'santri_id',
        'mitra_id',
        'jumlah',
        'jenis'
    ];

    protected $keyType = 'string';
}
