<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Nasabah extends Model
{
    protected $table = 'nasabah';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'rekening',
        'saldo_id',
        'nisn',
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
        'tahun',
        'password',
        'card',
        'saldo',
        'wa'
    ];


    protected $keyType = 'string';


    public function transaksi()
    {
        return $this->hasMany(Nasabah_transaksi::class, 'nasabah_id', 'id');
    }


    protected static function booted()
    {
        static::creating(function ($nasabah) {
            if (!$nasabah->rekening) {
                $query = Nasabah::orderBy('rekening', 'DESC')->first();
                $rekening = $query?->rekening ? substr($query->rekening, 3)  : 0;
                $nasabah->rekening = "NSB" . str_pad($rekening + 1, 5, 0, STR_PAD_LEFT);
            }
            $nasabah->id = Str::uuid();
        });
    }

    public function lembaga()
    {
        return $this->belongsTo(Saldo::class, 'saldo_id', 'id');
    }
}
