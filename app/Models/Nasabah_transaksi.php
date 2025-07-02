<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Nasabah_transaksi extends Model
{
    use HasFactory;
    protected $table = 'nasabah_transaksi';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'nasabah_id',
        'user_id',
        'credit',
        'debit',
        'keterangan',
        'ref',
        'ref_id',
        'created_at',
    ];

    protected $keyType = 'string';


    public function nasabah()
    {
        return $this->hasOne(Nasabah::class, 'id', 'nasabah_id');
    }


    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = Str::uuid();
        });
    }
}
