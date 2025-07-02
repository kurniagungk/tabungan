<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    protected $table = 'mitra';

    protected $fillable = [
        'id',
        'nama',
        'email',
        'password',
        'saldo'
    ];

    protected $keyType = 'string';
}
