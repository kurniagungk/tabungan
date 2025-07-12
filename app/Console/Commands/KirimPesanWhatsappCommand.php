<?php

namespace App\Console\Commands;

use App\Models\Whatsapp;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

            $telepon = preg_replace('/\D/', '', $nasabah->telepon); // Ambil hanya angka

            if (strpos($telepon, '0') === 0) {
                // Jika dimulai dengan 0, ganti ke 62
                $telepon = '62' . substr($telepon, 1);
            } elseif (strpos($telepon, '62') !== 0) {
                // Jika tidak dimulai dengan 62, tambahkan 62 di depan
                $telepon = '62' . $telepon;
            }

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
                    $this->error("Gagal kirim untuk ID: {$pesan->id}, {$jid}" . ' - ' . $response->json());
                }
            } catch (\Exception $e) {
                $pesan->update(['status' => 'gagal']);
                $this->error("Error kirim untuk ID: {$pesan->id} - {$jid}. Pesan error: " . $e->getMessage());
                Log::error($e);
            }
        }
    }
}
