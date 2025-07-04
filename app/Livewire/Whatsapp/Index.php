<?php

namespace App\Livewire\Whatsapp;

use App\Models\Saldo;

use App\Models\Setting;
use Livewire\Component;
use App\Models\Whatsapp;
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
    public $whatsappSession;
    public $saldo_id;




    public function mount()
    {

        $user = auth()->user();
        $admin = $user->hasRole('admin');
        if (!$admin) {

            $saldo = Saldo::where('id', $user->saldo_id)->first();

            $this->whatsappSession = $saldo->nama;
            $this->saldo_id = $user->saldo_id;
        }


        $this->whatsappUrl = Env('WHATSAPP_API_URL');
        $this->whatsappKey = Env('WHATSAPP_API_KEY');

        $setting = Setting::where('nama', 'whatsapp_api')->first();

        if ($setting->isi == 0)
            $this->status = false;
        else
            $this->status = true;
    }

    public function updatedSaldoId()
    {
        $saldo = Saldo::find($this->saldo_id);
        $this->whatsappSession = $saldo->nama;
        $this->qr = null;
        $this->dispatch('qr');
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
        )->delete($this->whatsappUrl . '/sessions/' . $this->whatsappSession);

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
            )->get($this->whatsappUrl . '/sessions/' . $this->whatsappSession);



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
            'sessionId' => $this->whatsappSession,
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
        )->get($this->whatsappUrl . '/sessions/' . $this->whatsappSession . '/status');



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

        $user = auth()->user();
        $admin = $user->hasRole('admin');

        $pesan = Whatsapp::withWhereHas('nasabah', function ($query) use ($user, $admin) {
            $query->select('id', 'nama', 'saldo_id')->when(!$admin, function ($query) use ($user) {
                $query->where('saldo_id', $user->saldo_id);
            });
        })->orderBy('created_at', 'desc')->paginate(10);

        $headers = [
            ['key' => 'nasabah.nama', 'label' => 'Nasabah'],
            ['key' => 'status', 'label' => 'Status'],
            ['key' => 'created_at', 'label' => ' tanggal']
        ];

        $dataSaldo = Saldo::all()->prepend((object)[
            'id' => '',
            'nama' => 'Pilih Saldo'
        ]);

        return view('livewire.whatsapp.index', compact('pesan', 'headers', 'dataSaldo'));
    }
}
