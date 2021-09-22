<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Whatapps extends Model
{
    use HasFactory;
    protected $table = 'whatapps';

    protected $fillable = [
        'nomer', 'nama', 'status'
    ];
}
