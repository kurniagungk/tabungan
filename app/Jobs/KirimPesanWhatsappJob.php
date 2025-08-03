<?php

namespace App\Jobs;

use App\Models\Whatsapp;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\Middleware\RateLimited;

class KirimPesanWhatsappJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(public Whatsapp $pesan) {}

    public function middleware(): array
    {
        return [new RateLimited('kirim-wa')];
    }

    public function backoff(): array
    {
        return [1800]; // 30 menit
    }

    public function handle(): void
    {

        if ($this->pesan->status == 'berhasil') {
            Log::info("Pesan WhatsApp ID {$this->pesan->id} sudah berhasil, melewati pengiriman.");
            return;
        }

        $nasabah = $this->pesan->nasabah;
        $lembaga = $nasabah?->lembaga;

        if (!$nasabah || !$nasabah->telepon || !$lembaga) {
            $this->pesan->update(['status' => 'gagal']);
            Log::error("Error WA ID {$this->pesan->id}: " . "Nasabah atau Lembaga tidak ditemukan.");
            return;
        }

        $setting = $lembaga->setting()->where('nama', 'whatsapp_session')->first();
        if (!$setting) {
            $this->pesan->update(['status' => 'gagal']);
            return;
        }

        $jid = $nasabah->telepon_whatsapp . '@s.whatsapp.net';
        $whatsappUrl = Env('WHATSAPP_API_URL');
        $whatsappKey = Env('WHATSAPP_API_KEY');

        $url = $whatsappUrl . '/' . rawurlencode($setting->isi) . '/messages/send';

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'x-api-key' => $whatsappKey,
            ])->post($url, [
                'jid' => $jid,
                'type' => 'number',
                'message' => ['text' => $this->pesan->pesan]
            ]);

            if (isset($response['status'])) {
                $this->pesan->update(['status' => 'berhasil']);
            } else {
                $this->pesan->update(['status' => 'gagal']);
                Log::error("WA gagal untuk ID {$this->pesan->id}", $response->json());
                throw new \Exception("Gagal mengirim WA untuk ID {$this->pesan->id}");
            }
        } catch (\Exception $e) {
            $this->pesan->update(['status' => 'gagal']);
            Log::error("Error WA ID {$this->pesan->id}: " . $e->getMessage());
            throw $e;
        }
    }
}
