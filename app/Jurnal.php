<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    protected $table = 'jurnal';

    protected $fillable = [
        'id',
        'mitra_id',
        'jumlah',
        'keterangan'
    ];

    protected $keyType = 'string';


    public function mitra()
    {
        return $this->hasOne(Mitra::class, 'id', 'mitra_id');
    }
}
