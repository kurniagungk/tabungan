<?php

namespace App\Http\Controllers;

use App\Models\Saldo;
use App\Models\Nasabah;
use App\Models\Setting;
use App\Models\Whatsapp;
use Illuminate\Http\Request;
use App\Models\WhatsappPesan;
use App\Models\Nasabah_transaksi;
use App\Jobs\KirimPesanWhatsappJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt; // Jika Anda menggunakan enkripsi Laravel

class WebhookController extends Controller
{
    /**
     * Handle incoming webhook requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleWebhook(Request $request)
    {
        // Ambil body JSON mentah (array)
        $payload = $request->json()->all() ?? [];

        $encryptionKey = env('WHATSAPP_API_KEY');

        // Jika ada encryptedData, coba decrypt dengan skema ivHex:cipherHex (key = sha256(secret))
        if (isset($payload['encryptedData'])) {
            if (! $encryptionKey) {
                Log::error('WHATSAPP_API_KEY not set, but received encrypted webhook data.');
                return response()->json(['message' => 'Server configuration error: Missing encryption key'], 500);
            }

            $decryptedJson = $this->decryptWebhookPayload((string) $payload['encryptedData'], $encryptionKey);

            if ($decryptedJson === null) {
                Log::error('Failed to decrypt webhook data (invalid format, key mismatch, or corrupted data).');
                return response()->json(['message' => 'Failed to decrypt data'], 400);
            }

            try {
                $payload = json_decode($decryptedJson, true, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {
                Log::error('Failed to decode decrypted webhook data: ' . $e->getMessage());
                return response()->json(['message' => 'Failed to decode decrypted data'], 400);
            }
        }

        // Validasi minimal
        if (!isset($payload['sessionId'], $payload['event'])) {
            Log::warning('Invalid payload received: missing sessionId or event.', $payload);
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        $sessionId = (string) $payload['sessionId'];
        $event     = (string) $payload['event'];
        $data      = $payload['data'] ?? null;

        $saldo = Saldo::where('nama', $sessionId)->first();
        if (!$saldo) {
            return response()->json(['message' => 'Saldo not found'], 404);
        }


        $setting = Setting::where('nama', 'whatsapp_webhook')
            ->whereHas('saldo', function ($query) use ($sessionId) {
                $query->where('nama', $sessionId);
            })
            ->first();


        if ($setting->isi == '1' && $event == "messages.upsert") {

            $message   = $payload["data"]['messages'] ?? null;

            $pesan = $message['message']['conversation'] ?? null;


            if ($pesan == "saldo" || "mutasi") {
                $nomer = $message['key']['remoteJid'] ?? null;
                if ($nomer) {
                    $nomer = preg_replace('/[^0-9]/', '', $nomer); // Hanya ambil angka
                    $variants = [
                        $nomer,                        // 628xxx
                        '0' . substr($nomer, 2),       // 08xxx
                        substr($nomer, 2),             // 8xxx
                    ];
                    $nasabah = Nasabah::whereIn('telepon', $variants)->first();
                    if ($nasabah) {
                        $pesan = strtolower($pesan);
                        if ($pesan == "saldo") {
                            $this->whatapps($nasabah, "Saldo Anda adalah: " . $nasabah->saldo);
                        } elseif ($pesan == "mutasi") {
                            $this->mutasi($nasabah);
                        }
                    } else {
                    }
                } else {
                }
            }
        } else {
        }


        if ($data !== null) {
        }

        // TODO: proses sesuai kebutuhanmu di sini...

        return response()->json(['message' => 'Webhook received successfully'], 200);
    }

    /**
     * Dekripsi format "<iv-hex>:<cipher-hex>" dengan AES-256-CBC.
     * Key: sha256(secret) bytes, IV: 16 byte dari iv-hex, ciphertext: hex.
     * Return: string plaintext UTF-8 atau null jika gagal.
     */
    private function decryptWebhookPayload(string $encrypted, string $secretKey): ?string
    {
        try {
            // Expect "ivHex:cipherHex"
            $parts = explode(':', $encrypted, 2);
            if (count($parts) !== 2) {
                return null;
            }

            [$ivHex, $ctHex] = $parts;
            if ($ivHex === '' || $ctHex === '' || (strlen($ivHex) % 2 !== 0) || (strlen($ctHex) % 2 !== 0)) {
                return null;
            }

            $iv     = @hex2bin($ivHex);
            $cipher = @hex2bin($ctHex);
            if ($iv === false || $cipher === false) {
                return null;
            }

            $keyBytes = hash('sha256', $secretKey, true); // 32 byte, sama dgn Node

            $plain = openssl_decrypt(
                $cipher,
                'aes-256-cbc',
                $keyBytes,
                OPENSSL_RAW_DATA, // PKCS#7 padding (default)
                $iv
            );

            return $plain === false ? null : $plain;
        } catch (\Throwable $e) {
            Log::error('Decrypt exception: ' . $e->getMessage());
            return null;
        }
    }

    public function whatapps($nasabah, $transaksi)
    {



        $wa = WhatsappPesan::where('jenis', 'saldo')->first();

        if (!$wa || $wa->status == "tidak")
            return;

        $replace = ['{nama}', '{saldo}', '{tanggal}'];
        $variable = [
            $nasabah->nama,
            'Rp. ' . number_format($nasabah->saldo, 2, ',', '.'),
            date('d-m-Y H:i',),

        ];
        $pesan = str_replace($replace, $variable, $wa->pesan);

        $whatsapp =  Whatsapp::create([
            'nasabah_id' => $nasabah->id,
            'transaksi_id' => null,
            'pesan' => $pesan,
            'status' => 'pending'
        ]);

        KirimPesanWhatsappJob::dispatch($whatsapp);
    }

    private function mutasi($nasabah)
    {
        $transaksi = Nasabah_transaksi::where('nasabah_id', $nasabah->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $mutasiText = '';

        foreach ($transaksi->reverse() as $item) {
            $tanggal = $item->created_at->format('d-m-Y H:i');

            // Tentukan jenis transaksi
            if ($item->credit > 0) {
                $jenis  = 'Setor Tunai';
                $jumlah = 'Rp. ' . number_format($item->credit, 2, ',', '.');
            } elseif ($item->debit > 0) {
                $jenis  = 'Tarik Tunai';
                $jumlah = 'Rp. ' . number_format($item->debit, 2, ',', '.');
            } else {
                $jenis  = 'Transaksi';
                $jumlah = 'Rp. 0,00';
            }

            $keterangan = $item->keterangan ?? '-';

            $mutasiText .= "Tanggal   : {$tanggal}\n";
            $mutasiText .= "Jenis     : {$jenis}\n";
            $mutasiText .= "Jumlah    : {$jumlah}\n";
            $mutasiText .= "Keterangan: {$keterangan}\n\n";
        }



        // Ambil template pesan mutasi dari tabel whatsapp_pesan
        $wa = WhatsappPesan::where('jenis', 'mutasi')->first();

        $replace = ['{nama}', '{saldo}', '{tanggal}', '{mutasi}'];
        $variable = [
            $nasabah->nama,
            'Rp. ' . number_format($nasabah->saldo, 2, ',', '.'),
            now()->format('d-m-Y H:i'),
            trim($mutasiText),
        ];

        // Replace semua placeholder di template
        $pesan = str_replace($replace, $variable, $wa->pesan);

        $whatsapp =  Whatsapp::create([
            'nasabah_id' => $nasabah->id,
            'transaksi_id' => null,
            'pesan' => $pesan,
            'status' => 'pending'
        ]);

        KirimPesanWhatsappJob::dispatch($whatsapp);
    }
}
