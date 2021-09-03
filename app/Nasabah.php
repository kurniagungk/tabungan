<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nasabah extends Model
{
    protected $table = 'nasabah';

    protected $fillable = [
        'id',
        'rekening',
        'nama',
        'tanggal_lahir',
        'tempat_lahir',
        'alamat',
        'asrama',
        'jenis_kelamin',
        'wali',
        'foto',
        'telepon',
        'status',
        'password',
        'card',
        'saldo'
    ];


    protected $keyType = 'string';


    public function transaksi()
    {
        return $this->hasMany(Nasabah_transaksi::class, 'nasabah_id', 'id');
    }


    protected static function booted()
    {
        static::creating(function ($nasabah) {
            $query = Nasabah::first();
            $rekening = $query?->rekening ? substr($query->rekening, 3)  : 0;
            $nasabah->rekening = "NSB" . str_pad($rekening + 1, 5, 0, STR_PAD_LEFT);
        });
    }
}
