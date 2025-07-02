<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;



class Transaksi extends Model
{
    protected $table = 'transaksi';
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'credit',
        'debit',
        'keterangan',
        'ref'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
}
