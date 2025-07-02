<?php

namespace App\Models;

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
        return $this->hasOne(User::class, 'id', 'mitra_id');
    }
}
