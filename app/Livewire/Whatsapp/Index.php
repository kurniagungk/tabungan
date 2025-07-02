<?php

namespace App\Livewire\Whatsapp;

use App\Models\Setting;

use App\Models\Whatsapp;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Http;

class Index extends Component
{
    use WithPagination;


    public $perPage = 10;
    public $status;
    public $qr;
    public $sesion = false;
    public $server = true;
    public $whatsappUrl;
    public $whatsappKey;

    #[Layout('components.layouts.app')]
    public function mount()
    {

        $this->whatsappUrl = Env('WHATSAPP_API_URL');
        $this->whatsappKey = Env('WHATSAPP_API_KEY');

        $this->findSesion();
        $setting = Setting::where('nama', 'whatsapp_api')->first();

        if ($setting->isi == 0)
            $this->status = false;
        else
            $this->status = true;
    }

    public function updatedStatus()
    {
        $setting = Setting::where('nama', 'whatsapp_api')->update([
            'isi' => $this->status
        ]);

        if ($this->status == 0 && $this->sesion == true) {
            $this->deleteSession();
            $this->status = false;
        }
    }

    public function deleteSession()
    {
        $sesion = Http::withHeaders(
            [
                'Content-Type' => 'application/json',
                'x-api-key' => $this->whatsappKey
            ]
        )->delete($this->whatsappUrl . '/sessions/tabungan');

        $this->sesion = false;
    }

    public function findSesion()
    {

        try {

            $sesion = Http::withHeaders(
                [
                    'Content-Type' => 'application/json',
                    'x-api-key' => $this->whatsappKey
                ]
            )->get($this->whatsappUrl . '/sessions/tabungan');


            $this->server = true;


            if (isset($sesion->json()['error'])) {

                $this->createSession();
            } else {
                $this->statusSession();
            }
        } catch (\Exception $ex) {

            $this->server = false;
        }
    }

    public function createSession()
    {
        $create = Http::withHeaders(
            [
                'Content-Type' => 'application/json',
                'x-api-key' => $this->whatsappKey
            ]
        )->post($this->whatsappUrl . '/sessions/add', [
            'sessionId' => 'tabungan',
        ]);



        if (isset($create->json()['qr']))
            $this->qr = $create->json()['qr'];
    }

    public function statusSession()
    {
        $status = Http::withHeaders(
            [
                'Content-Type' => 'application/json',
                'x-api-key' => $this->whatsappKey
            ]
        )->get($this->whatsappUrl . '/sessions/tabungan/status');



        if (isset($status->json()['status'])) {

            if ($status->json()['status'] == "connected") {
                $this->qr = asset('images/whatsapp.png');
                $this->sesion = true;
            } else {
                $this->deleteSession();
                $this->createSession();
            }
        } else {
            $this->deleteSession();
            $this->createSession();
        }
    }


    public function dc()
    {
        $this->deleteSession();
        $this->findSesion();
    }


    public function render()
    {

        $pesan = Whatsapp::orderBy('created_at', 'desc')->paginate(10);


        $headers = [
            ['key' => 'nasabah.nama', 'label' => 'Nasabah'],
            ['key' => 'status', 'label' => 'Status'],
            ['key' => 'created_at', 'label' => ' tanggal']
        ];

        return view('livewire.whatsapp.index', compact('pesan', 'headers'));
    }
}
