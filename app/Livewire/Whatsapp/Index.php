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
    public $whatsappSession;
    public $saldo_id;
    public $statusId = 'semua';

    public array $selected = [];




    public function mount()
    {

        $user = auth()->user();
        $admin = $user->hasRole('admin');
        if (!$admin) {

            $saldo = Saldo::where('id', $user->saldo_id)->first();

            $this->whatsappSession = $saldo->nama;
            $this->saldo_id = $user->saldo_id;
        }




        $setting = Setting::where('nama', 'whatsapp_api')->first();

        if ($setting->isi == 0)
            $this->status = false;
        else
            $this->status = true;
    }

    public function whatsapp()
    {

        $whatsappUrl = Env('WHATSAPP_API_URL');
        $whatsappKey = Env('WHATSAPP_API_KEY');


        return [

            'whatsappUrl' => $whatsappUrl,
            'whatsappKey' => $whatsappKey

        ];
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

        $session = rawurlencode($this->whatsappSession);
        $whatsapp = $this->whatsapp();

        $sesion = Http::withHeaders(
            [
                'Content-Type' => 'application/json',
                'x-api-key' => $whatsapp["whatsappKey"]
            ]
        )->delete($whatsapp["whatsappUrl"] . '/sessions/' .  $session);

        $this->sesion = false;
    }

    public function findSesion()
    {



        $whatsapp = $this->whatsapp();

        $session = rawurlencode($this->whatsappSession);

        try {

            $sesion = Http::withHeaders(
                [
                    'Content-Type' => 'application/json',
                    'x-api-key' => $whatsapp["whatsappKey"]
                ]
            )->get($whatsapp["whatsappUrl"] . '/sessions/' .  $session);



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


        $whatsapp = $this->whatsapp();

        $create = Http::withHeaders(
            [
                'Content-Type' => 'application/json',
                'x-api-key' => $whatsapp["whatsappKey"]
            ]
        )->post($whatsapp["whatsappUrl"] . '/sessions/add', [
            'sessionId' => $this->whatsappSession,
        ]);


        if (isset($create->json()['qr']))
            $this->qr = $create->json()['qr'];
    }

    public function statusSession()
    {

        $session = rawurlencode($this->whatsappSession);

        $whatsapp = $this->whatsapp();

        $status = Http::withHeaders(
            [
                'Content-Type' => 'application/json',
                'x-api-key' => $whatsapp["whatsappKey"]
            ]
        )->get($whatsapp["whatsappUrl"] . '/sessions/' .  $session . '/status');



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

    public function ulangi($id)
    {
        Whatsapp::where('id', $id)->update(['status' => 'pending']);
    }


    public function ulangiAll()
    {

        $id = $this->selected;

        Whatsapp::whereIn('id', $id)->where('status', 'gagal')->update(['status' => 'pending']);
    }

    public function render()
    {

        $saldo_id = $this->saldo_id;
        $statusId = $this->statusId;


        $pesan = Whatsapp::withWhereHas('nasabah', function ($query) use ($saldo_id) {
            $query->select('id', 'nama', 'saldo_id')->where('saldo_id', $saldo_id);
        })->when($this->statusId != 'semua', function ($query) use ($statusId) {
            return $query->where('status', $statusId);
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

        $statusSelect = [
            ['value' => 'semua', 'label' => 'Semua'],
            ['value' => 'pending', 'label' => 'Pending'],
            ['value' => 'gagal', 'label' => 'Gagal'],
            ['value' => 'berhasil', 'label' => 'Berhasil'],
        ];

        return view('livewire.whatsapp.index', compact('pesan', 'headers', 'dataSaldo', 'statusSelect'));
    }
}
