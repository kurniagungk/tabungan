<?php

namespace App\Livewire\Whatsapp;

use App\Models\Saldo;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

/**
 * Komponen Livewire untuk antarmuka obrolan WhatsApp.
 *
 * Komponen ini memungkinkan pengguna untuk berinteraksi dengan API WhatsApp,
 * mengambil kontak, pesan obrolan, dan mengirim pesan.
 */
class Chat extends Component
{

    /**
     * ID Saldo yang dipilih.
     *
     * @var int|null
     */
    public  $saldo_id;

    /**
     * Sesi WhatsApp yang digunakan.
     *
     * Biasanya diambil dari nama Saldo.
     * @var string|null
     */
    public  $whatsappSession;

    /**
     * Daftar kontak WhatsApp yang tersedia.
     *
     * @var array
     */
    public  $contacts = [];

    /**
     * Daftar pesan obrolan yang ditampilkan.
     *
     * @var array
     */
    public  $chats = [];

    /**
     * ID kontak yang saat ini dipilih.
     *
     * @var string|null
     */
    public $contactId;

    /**
     * Pesan yang akan dikirim.
     *
     * @var string|null
     */
    public $message;

    /**
     * Siklus hidup component, dipanggil setelah component di-mount.
     *
     * Mengambil informasi user dan menentukan saldo_id berdasarkan role user.
     * Hanya user non-admin yang memiliki saldo_id.
     *
     * @return void
     */
    public function mount()
    {

        $user = auth()->user();
        $admin = $user->hasRole('admin');

        $saldo_id = null;

        if (!$admin) {
            $saldo_id = $user->saldo_id;
        }
    }

    /**
     * Mengembalikan konfigurasi API WhatsApp.
     *
     * Mengambil URL dan Key API WhatsApp dari environment variables.
     *
     * @return array
     */
    public function whatsapp()
    {

        $whatsappUrl = Env('WHATSAPP_API_URL');
        $whatsappKey = Env('WHATSAPP_API_KEY');


        return [

            'whatsappUrl' => $whatsappUrl,
            'whatsappKey' => $whatsappKey

        ];
    }

    /**
     * Mengambil daftar kontak WhatsApp dari API.
     *
     * Menggunakan `whatsappSession` untuk mengambil daftar kontak terkait.
     * Menyimpan daftar kontak ke properti `$contacts`.
     *
     * @return void
     */
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

    /**
     * Menetapkan kontak yang dipilih dan mengambil riwayat obrolan.
     *
     * Menggunakan `contactId` dan `whatsappSession` untuk mengambil daftar pesan.
     * Menyimpan daftar pesan ke properti `$chats` dan mengirimkan event `chat-loaded`.
     *
     * @param string $id ID kontak yang dipilih.
     * @return void
     */
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

    /**
     * Dipanggil ketika `$saldo_id` diperbarui.
     *
     * Mengambil nama Saldo berdasarkan `$saldo_id` dan menetapkannya ke `$whatsappSession`.
     * Kemudian memanggil `getContact()` untuk memperbarui daftar kontak.
     *
     * @return void
     */
    public function updatedSaldoId()
    {
        $saldo = Saldo::find($this->saldo_id);
        $this->whatsappSession = $saldo->nama;
        $this->getContact();
    }

    /**
     * Mengirim pesan ke kontak yang dipilih.
     *
     * Membuat struktur data pesan (chat) dan menambahkannya ke awal array `$chats`.
     * Mereset properti `$message` setelah pesan ditambahkan.
     *
     * @return void
     */
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

    /**
     * Merender tampilan komponen.
     *
     * Mengambil semua data Saldo dan menambahkannya ke tampilan.
     * Menyiapkan opsi "default Setting" sebagai opsi pertama.
     *
     * @return \Illuminate\Contracts\View\View
     */
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
