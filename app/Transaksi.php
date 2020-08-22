<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

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


    public function mitra()
    {
        return $this->hasOne(Mitra::class, 'id', 'mitra_id');
    }

    public function nasabah()
    {
        return $this->hasOne(Nasabah::class, 'id', 'santri_id');
    }
}
