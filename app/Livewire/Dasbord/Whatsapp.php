<?php

namespace App\Livewire\Dasbord;

use App\Models\Setting;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Livewire\Component;

class Whatsapp extends Component
{


    public array $sessions = [];
    public int $total = 0;
    public int $online = 0;
    public int $offline = 0;
    public ?string $cachedAt = null;

    public int $ttlMinutes = 60; // cache 1 jam

    protected function whatsapp(): array
    {

        return [
            'whatsappUrl' =>  config('whatsapp.api_url'),
            'whatsappKey' => config('whatsapp.api_key'),
        ];
    }


    public function refreshCache(): void
    {
        Cache::forget($this->cacheKey());
        $this->loadData(force: true);
    }

    protected function cacheKey(): string
    {
        return 'wa:status:sessions';
    }

    public function loadData(bool $force = false): void
    {
        $minutes = max(1, $this->ttlMinutes);

        $data = $force
            ? $this->fetchSessions()
            : Cache::remember(
                $this->cacheKey(),
                now()->addMinutes($minutes),
                fn() => $this->fetchSessions()
            );

        $this->sessions = $data['sessions'];
        $this->cachedAt = $data['cached_at'];

        $this->total   = count($this->sessions);
        $this->online  = collect($this->sessions)->where('status', 'online')->count();
        $this->offline = $this->total - $this->online;
    }

    protected function fetchSessions(): array
    {
        $wa = $this->whatsapp();

        $items = Setting::where('nama', 'whatsapp_session')->whereNotNull('saldo_id')->get();

        $sessions = $items->map(function ($row) use ($wa) {
            $sessionId = trim((string) $row->isi);
            $encoded   = rawurlencode($sessionId);

            try {
                $resp = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'x-api-key'    => $wa['whatsappKey'] ?? '',
                ])
                    ->timeout(10)
                    ->retry(2, 150)
                    ->get(rtrim($wa['whatsappUrl'] ?? '', '/') . "/sessions/{$encoded}/status");

                $json = $resp->json() ?? [];

                // kalau API balikin error, langsung offline
                if (isset($json['error'])) {
                    return [
                        'name' => Str::headline($sessionId),
                        'session_id' => $sessionId,
                        'status' => 'offline',
                        'last_active_for_humans' => '—',
                    ];
                }

                $raw = strtolower((string) Arr::get($json, 'status', 'offline'));

                // normalisasi status
                // AUTHENTICATED -> dianggap online
                $isOn = in_array($raw, ['connected', 'online', 'authenticated'], true);
                $status = $isOn ? 'online' : 'offline';

                // ambil lastActivityAt jika ada
                $ts = Arr::get($json, 'lastActivityAt');
                $lastHuman = '—';
                if ($ts) {
                    try {
                        $lastHuman = Carbon::parse($ts)->diffForHumans();
                    } catch (\Throwable $e) {
                        $lastHuman = '—';
                    }
                }

                return [
                    'name' => Str::headline($sessionId),
                    'session_id' => $sessionId,
                    'status' => $status,
                    'last_active_for_humans' => $lastHuman,
                ];
            } catch (\Throwable $e) {
                // kalau request gagal total
                return [
                    'name' => Str::headline($sessionId),
                    'session_id' => $sessionId,
                    'status' => 'offline',
                    'last_active_for_humans' => '—',
                ];
            }
        })->values()->all();

        return [
            'sessions' => $sessions,
            'cached_at' => now()->toDateTimeString(),
        ];
    }



    public function render()
    {
        return view('livewire.dasbord.whatsapp');
    }
}
