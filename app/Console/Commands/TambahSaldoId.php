<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Saldo;
use App\Models\Nasabah;
use App\Models\Setting;
use Illuminate\Support\Str;
use App\Models\WhatsappPesan;
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


        $dataSaldo = Saldo::with('whatsappPesan', 'setting')->get();

        // dd($dataSaldo);

        foreach ($dataSaldo as $s) {

            if ($s->setting->isEmpty()) {

                $defaultSettings = Setting::whereNull('saldo_id')
                    ->get();

                foreach ($defaultSettings as $default) {
                    Setting::create([
                        'saldo_id' => $s->id,
                        'nama' => $default->nama,
                        'isi' => $default->isi,
                    ]);
                }
            }

            if ($s->whatsappPesan->isEmpty()) {

                $defaultPesan = WhatsappPesan::whereNull('saldo_id')
                    ->get();


                foreach ($defaultPesan as $pesan) {
                    WhatsappPesan::create([
                        'saldo_id' => $s->id,
                        'pesan' => $pesan->pesan,
                        'jenis' => $pesan->jenis,
                        'status' => $pesan->status
                    ]);
                }
            }
        }

        $this->info("Berhasil memperbarui $saldo->nama nasabah.");
    }
}
