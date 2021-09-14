<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Nasabah_transaksi extends Model
{
    use HasFactory;
    protected $table = 'nasabah_transaksi';

    protected $fillable = [
        'id',
        'nasabah_id',
        'credit',
        'debit',
        'keterangan',
        'ref',
        'ref_id',
        'created_at',
    ];

    protected $keyType = 'string';


    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
}