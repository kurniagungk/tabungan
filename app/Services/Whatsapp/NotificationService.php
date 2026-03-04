<?php

namespace App\Services\Whatsapp;

use App\Jobs\KirimPesanWhatsappJob;
use App\Models\Nasabah;
use App\Models\Whatsapp;
use Illuminate\Support\Carbon;

class NotificationService
{
    public function sendToNasabahs(iterable $nasabahs, string $template, string $jenis = 'notifikasi'): int
    {
        $count = 0;

        foreach ($nasabahs as $nasabah) {
            if (!$nasabah instanceof Nasabah) {
                continue;
            }

            $pesan = $this->renderMessage($template, $nasabah);

            $whatsapp = Whatsapp::create([
                'nasabah_id' => $nasabah->id,
                'transaksi_id' => null,
                'pesan' => $pesan,
                'jenis' => $jenis,
                'status' => 'pending',
            ]);

            KirimPesanWhatsappJob::dispatch($whatsapp);
            $count++;
        }

        return $count;
    }

    private function renderMessage(string $template, Nasabah $nasabah): string
    {
        $replace = ['{nama}', '{saldo}', '{jumlah}', '{tanggal}', '{keterangan}'];
        $variable = [
            $nasabah->nama,
            'Rp. ' . number_format((float) $nasabah->saldo, 2, ',', '.'),
            '-',
            Carbon::now()->format('d-m-Y H:i'),
            '-',
        ];

        return str_replace($replace, $variable, $template);
    }
}
