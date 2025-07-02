<?php

namespace App\Console\Commands;

use App\Models\Whatsapp;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class KirimPesanWhatsappCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:whatsapp:kirim {status=pending}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $status = $this->argument('status');

        $whatsapps = Whatsapp::where('status', $status)->get();

        if ($whatsapps->isEmpty()) {
            $this->info('Tidak ada pesan pending.');
            return;
        }

        $whatsappUrl = Env('WHATSAPP_API_URL');
        $whatsappKey = Env('WHATSAPP_API_KEY');

        foreach ($whatsapps as $pesan) {
            $nasabah = $pesan->nasabah;

            if (!$nasabah || !$nasabah->telepon) {
                $pesan->update(['status' => 'failed']);
                $this->warn("Nasabah tidak valid untuk ID WhatsApp: {$pesan->id}");
                $pesan->update(['status' => 'gagal']);
                continue;
            }

            $telepon = preg_replace('/\D/', '', $nasabah->telepon); // bersihkan ke angka saja
            $jid = $telepon . '@s.whatsapp.net';

            try {

                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'x-api-key' => $whatsappKey,
                ])->post($whatsappUrl . '/tabungan/messages/send', [
                    'jid' => $jid, // ganti dengan ID grup sebenarnya
                    'type' => 'number',
                    'message' => [
                        'text' => $pesan->pesan
                    ]
                ]);

                if (isset($response['status'])) {
                    $pesan->update(['status' => 'berhasil']);
                    $this->info("Berhasil kirim untuk ID: {$pesan->id}");
                } else {
                    $pesan->update(['status' => 'gagal']);
                    $this->error("Gagal kirim untuk ID: {$pesan->id}");
                }
            } catch (\Exception $e) {
                $pesan->update(['status' => 'gagal']);
                $this->error("Gagal kirim untuk ID: {$pesan->id}");
            }
        }
    }
}
