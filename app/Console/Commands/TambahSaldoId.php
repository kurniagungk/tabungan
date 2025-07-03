<?php

namespace App\Console\Commands;

use App\Models\Saldo;
use App\Models\Nasabah;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Console\Command;

class TambahSaldoId extends Command
{
    protected $signature = 'app:nasabah:tambah-saldo-id {saldo_id}';
    protected $description = 'Menambahkan saldo_id ke semua data nasabah yang belum memiliki saldo_id';

    public function handle()
    {
        $saldoId = $this->argument('saldo_id');

        $saldo = Saldo::find($saldoId);

        if (!$saldo) {
            $this->error("Saldo dengan ID $saldoId tidak ditemukan.");
            return;
        }

        $nasabah = Nasabah::whereNull('saldo_id')->update(['saldo_id' => $saldo->id]);
        $user = User::whereNull('saldo_id')->update(['saldo_id' => $saldo->id]);

        $this->info("Berhasil memperbarui $saldo->nama nasabah.");
    }
}
