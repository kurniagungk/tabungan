<?php

namespace App\Livewire\Whatsapp;

use App\Models\Saldo;

use Mary\Traits\Toast;
use App\Models\Setting;
use Livewire\Component;
use App\Models\Whatsapp;
use Livewire\WithPagination;
use App\Jobs\KirimPesanWhatsappJob;
use Illuminate\Support\Facades\Http;

class Index extends Component
{
    use WithPagination,   Toast;

    public $perPage = 10;
    public $status;
    public $qr;
    public $sesion = false;
    public $server = true;
    public $whatsappSession;
    public $saldo_id;
    public $whatsappHook;
    public $statusId = 'semua';

    public array $selected = [];




    public function mount()
    {

        $user = auth()->user();
        $admin = $user->hasRole('admin');
        $saldo_id = null;
        if (!$admin) {

            $saldo = Saldo::where('id', $user->saldo_id)->first();

            $this->saldo_id = $user->saldo_id;
        }




        $setting = Setting::where('nama', 'whatsapp_api')->where('saldo_id',  $this->saldo_id)->first();
        $hook = Setting::where('nama', 'whatsapp_webhook')->where('saldo_id',  $this->saldo_id)->first();

        $settingSesion = Setting::where('nama', 'whatsapp_session')->where('saldo_id', $this->saldo_id)->first();

        $this->whatsappSession = $settingSesion ? $settingSesion->isi : null;


        if ($setting->isi == 0)
            $this->status = false;
        else
            $this->status = true;

        $this->whatsappHook = $hook->isi == 1 ? true : false;
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

        $settingSesion = Setting::where('nama', 'whatsapp_session')->where('saldo_id', $this->saldo_id)->first();
        $hook = Setting::where('nama', 'whatsapp_webhook')->where('saldo_id',  $this->saldo_id)->first();
        $setting = Setting::where('nama', 'whatsapp_api')->where('saldo_id',  $this->saldo_id)->first();

        $this->whatsappSession = $settingSesion ? $settingSesion->isi : null;
        $this->whatsappHook = $hook->isi == 1 ? true : false;
        $this->status = $setting->isi == 1 ? true : false;

        $this->dispatch('qr');
    }


    public function updatedStatus()
    {
        $value = $this->status ? 1 : 0;

        $setting = Setting::where('nama', 'whatsapp_api')->where('saldo_id', $this->saldo_id)->update([
            'isi' => $value
        ]);

        if ($this->status == 0 && $this->sesion == true) {
            $this->deleteSession();
            $this->status = false;
        }
    }

    public function updatedWhatsappHook()
    {

        $value = $this->whatsappHook ? 1 : 0;

        $setting = Setting::where('nama', 'whatsapp_webhook')->where('saldo_id', $this->saldo_id)->update([
            'isi' => $value
        ]);
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

        $appUrl = config('app.url');


        $create = Http::withHeaders(
            [
                'Content-Type' => 'application/json',
                'x-api-key' => $whatsapp["whatsappKey"]
            ]
        )->post($whatsapp["whatsappUrl"] . '/sessions/add', [
            'sessionId' => $this->whatsappSession,
            'webhookUrl' =>  $appUrl . '/api/webhooks/whatsapp',
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
        $whatsapp = Whatsapp::find($id);

        $whatsapp->status = 'pending';
        $whatsapp->save();

        KirimPesanWhatsappJob::dispatch($whatsapp);

        $this->selected = [];
    }


    public function ulangiAll()
    {

        $id = $this->selected;

        $whatsapp = Whatsapp::whereIn('id', $id)->where('status', 'gagal')->get();

        foreach ($whatsapp as $pesan) {
            $pesan->status = 'pending';
            $pesan->save();

            KirimPesanWhatsappJob::dispatch($pesan);
        }

        $this->selected = [];
    }

    public function saveSessionNama()
    {
        $this->validate([
            'whatsappSession' => 'required'
        ]);

        $setting = Setting::updateOrCreate(
            ['nama' => 'whatsapp_session', 'saldo_id' => $this->saldo_id],
            ['isi' => $this->whatsappSession]
        );

        $this->dispatch('qr');

        $this->success(
            'WhatsApp Session berhasil disimpan.',
            timeout: 5000,
            position: 'toast-top toast-end'
        );
    }

    public function resetSesionNama()
    {

        $saldo = Saldo::find($this->saldo_id);
        $this->whatsappSession = $saldo->nama;

        Setting::updateOrCreate(
            ['nama' => 'whatsapp_session', 'saldo_id' => $this->saldo_id],
            ['isi' => $this->whatsappSession]
        );


        $this->dispatch('qr');
        $this->success(
            'WhatsApp Session berhasil direset.',
            timeout: 5000,
            position: 'toast-top toast-end'
        );
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
