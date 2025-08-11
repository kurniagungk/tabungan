<?php

namespace App\Livewire\Whatsapp;

use App\Models\Saldo;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class Chat extends Component
{

    public  $saldo_id;

    public  $whatsappSession;

    public  $contacts = [];
    public  $chats = [];

    public $contactId;
    public $message;

    public function mount()
    {

        $user = auth()->user();
        $admin = $user->hasRole('admin');

        $saldo_id = null;

        if (!$admin) {
            $saldo_id = $user->saldo_id;
        }
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

    public function getContact()
    {
        $whatsapp = $this->whatsapp();

        $session = rawurlencode($this->whatsappSession);

        try {

            $contacts = Http::withHeaders(
                [
                    'Content-Type' => 'application/json',
                    'x-api-key' => $whatsapp["whatsappKey"]
                ]
            )->get($whatsapp["whatsappUrl"] . '/' .  $session . '/contacts');



            if (isset($contacts->json()['error'])) {
            } else {
                $this->contacts = $contacts->json()['data'];
            }
        } catch (\Exception $ex) {
        }
    }

    public function setContact($id)
    {

        $this->contactId = $id;

        $whatsapp = $this->whatsapp();

        $session = rawurlencode($this->whatsappSession);

        try {

            $messages = Http::withHeaders(
                [
                    'Content-Type' => 'application/json',
                    'x-api-key' => $whatsapp["whatsappKey"]
                ]
            )->get($whatsapp["whatsappUrl"] . '/' .  $session . '/chats/' . $id);

            if (isset($messages->json()['error'])) {
            } else {
                $this->chats = $messages->json()['data'];
                $this->dispatch('chat-loaded');
            }
        } catch (\Exception $ex) {
        }

        $this->getContact();
    }

    public function updatedSaldoId()
    {
        $saldo = Saldo::find($this->saldo_id);
        $this->whatsappSession = $saldo->nama;
        $this->getContact();
    }

    public function sendMessage()
    {




        $chat = [
            'key' => [
                'fromMe' => true,
            ],
            'message' => [
                'conversation' => $this->message,
            ]
        ];

        $this->reset('message');

        array_unshift($this->chats, $chat);
    }

    public function render()
    {
        $dataSaldo = Saldo::all()->prepend((object)[
            'id' => null,
            'nama' => 'deafult Setting',
        ]);


        return view('livewire.whatsapp.chat', [
            'dataSaldo' => $dataSaldo,
        ]);
    }
}
