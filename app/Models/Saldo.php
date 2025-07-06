<?php

namespace App\Models;

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

    public function nasabah()
    {
        return $this->hasMany(Nasabah::class);
    }

    public function user()
    {
        return $this->hasMany(User::class, 'saldo_id', 'id');
    }

    public function whatsappPesan()
    {
        return $this->hasMany(WhatsappPesan::class, 'saldo_id', 'id');
    }

    public function setting()
    {
        return $this->hasMany(Setting::class,   'saldo_id', 'id');
    }
}
