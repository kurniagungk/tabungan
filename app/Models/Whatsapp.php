<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Whatsapp extends Model
{
    use HasFactory, HasUlids;
    protected $table = 'whatsapp';
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'transaksi_id',
        'nasabah_id',
        'pesan',
        'jenis',
        'status',
    ];

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }
}
