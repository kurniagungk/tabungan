<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappPesan extends Model
{
    use HasFactory;
    protected $table = 'whatsapp_pesan';

    protected $fillable = [
        'saldo_id',
        'jenis',
        'pesan',
        'status',
    ];
}
