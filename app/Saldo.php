<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saldo extends Model
{
    use HasFactory;
    protected $table = 'saldo';

    public $timestamps = false;

    protected $fillable = [
        'nama',
        'jumlah',
    ];
}
