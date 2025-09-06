<?php

namespace App\Livewire\Dasbord;

use App\Models\Setting;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Nasabah;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

/**
 * Komponen Livewire untuk menampilkan dashboard.
 *
 * Komponen ini menampilkan statistik terkait nasabah, termasuk jumlah total nasabah,
 * total saldo, jumlah saldo nasabah aktif, dan jumlah saldo nasabah tidak aktif.
 * Data yang ditampilkan difilter berdasarkan role user yang login:
 * - Admin: Dapat melihat semua data.
 * - User Biasa: Hanya dapat melihat data yang terkait dengan saldo_id mereka.
 */
class Index extends Component
{
    public string $whatsappSessionStatus;

    public function mount()
    {
        $this->whatsappSessionStatus = $this->fetchSessions();
    }

    protected function whatsapp(): array
    {
        return [
            'whatsappUrl' =>  env('WHATSAPP_API_URL'),
            'whatsappKey' => env('WHATSAPP_API_KEY'),
        ];
    }

    protected function fetchSessions(): string
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $whatsappConfig = $this->whatsapp();
        $cacheKey = 'whatsapp_session_status_' . $user->saldo_id;

        return Cache::remember($cacheKey, 60 * 60, function () use ($user, $whatsappConfig) {
            $item = Setting::query()
                ->where('nama', 'whatsapp_session')
                ->where('saldo_id', $user->saldo_id)
                ->first();


            if (!$item) {
                return 'tidak aktif'; // Nilai default jika tidak ada item
            }

            $sessionId = trim((string) $item->isi);
            $encodedSessionId = rawurlencode($sessionId);

            $whatsappSessionStatus = 'tidak aktif'; // Default value

            try {
                $resp = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'x-api-key' => $whatsappConfig['whatsappKey'] ?? '',
                ])
                    ->timeout(10)
                    ->retry(2, 150)
                    ->get(rtrim($whatsappConfig['whatsappUrl'] ?? '', '/') . "/sessions/{$encodedSessionId}/status");

                $json = $resp->json() ?? [];

                // kalau API balikin error, langsung offline
                if (isset($json['error'])) {
                    $whatsappSessionStatus = 'tidak aktif'; // Asumsikan offline karena error
                } else {
                    $raw = strtolower((string) Arr::get($json, 'status', 'offline'));

                    // normalisasi status
                    // AUTHENTICATED -> dianggap online
                    $isOn = in_array($raw, ['connected', 'online', 'authenticated'], true);
                    $whatsappSessionStatus = $isOn ? 'aktif' : 'tidak aktif'; // Set status berdasarkan $isOn
                }
            } catch (\Throwable $e) {
                // kalau request gagal total
                Log::error('Failed to fetch session status for session ID: ' . $sessionId, [
                    'exception' => $e,
                ]);

                $whatsappSessionStatus = 'tidak aktif'; // Asumsikan offline karena error
            }

            return $whatsappSessionStatus;
        });
    }




    /**
     * Merender view komponen.
     *
     * Mengambil data statistik nasabah dan meneruskannya ke view `livewire.dasbord.index`.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        // Mendapatkan user yang sedang login.
        $user = auth()->user();

        // Memeriksa apakah user memiliki role 'admin'.
        $admin = $user->hasRole('admin');

        // Menghitung jumlah total saldo nasabah (difilter berdasarkan saldo_id jika user bukan admin).
        $saldo = Nasabah::when(!$admin, function ($query) use ($user) {
            return $query->where('saldo_id', $user->saldo_id);
        })->sum('saldo');

        // Menghitung jumlah total saldo nasabah dengan status 'aktif' (difilter berdasarkan saldo_id jika user bukan admin).
        $aktif = Nasabah::when(!$admin, function ($query) use ($user) {
            return $query->where('saldo_id', $user->saldo_id);
        })->where('status', 'aktif')->sum('saldo');

        // Menghitung jumlah total saldo nasabah dengan status 'tidak' (difilter berdasarkan saldo_id jika user bukan admin).
        $tidak = Nasabah::when(!$admin, function ($query) use ($user) {
            return $query->where('saldo_id', $user->saldo_id);
        })->where('status', 'tidak')->sum('saldo');

        // Menghitung jumlah total nasabah (difilter berdasarkan saldo_id jika user bukan admin).
        $nasabah = Nasabah::when(!$admin, function ($query) use ($user) {
            return $query->where('saldo_id', $user->saldo_id);
        })->count();

        // Membuat array asosiatif yang berisi data statistik nasabah.
        $data = [
            'jumlahNasaba' => $nasabah,
            'saldo' => $saldo,
            'aktif' => $aktif,
            'tidak' => $tidak,
        ];

        $sessionsData = $this->fetchSessions();

        // Merender view 'livewire.dasbord.index' dan meneruskan data ke view.
        return view('livewire.dasbord.index', ['data' => $data, 'sessionsData' => $sessionsData]);
    }
}
