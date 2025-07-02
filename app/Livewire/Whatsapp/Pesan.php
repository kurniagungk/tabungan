<?php

namespace App\Livewire\Whatsapp;

use Mary\Traits\Toast;
use Livewire\Component;
use App\Models\WhatsappPesan;
use Livewire\Attributes\Validate;


class Pesan extends Component
{

    use Toast;

    #[Validate('required')]
    public $pesan_tarik;
    #[Validate('required')]
    public $pesan_setor;
    #[Validate('required')]
    public $status_tarik;
    #[Validate('required')]
    public $status_setor;


    public function mount()
    {
        $tarik = WhatsappPesan::where('jenis', 'tarik')->first();
        $setor = WhatsappPesan::where('jenis', 'setor')->first();

        $this->pesan_tarik = $tarik->pesan;
        $this->pesan_setor = $setor->pesan;
        $this->status_tarik = $tarik->status;
        $this->status_setor = $setor->status;
    }

    public function simpan()
    {
        $this->validate();

        $tarik = WhatsappPesan::where('jenis', 'tarik')->first();
        $setor = WhatsappPesan::where('jenis', 'setor')->first();
        $tarik->pesan = $this->pesan_tarik;
        $tarik->status = $this->status_tarik;
        $setor->pesan = $this->pesan_setor;
        $setor->status = $this->status_setor;
        $tarik->save();
        $setor->save();

        $this->toast(
            type: 'success',
            title: 'Berhasil Disimpan',
            description: null,                  // optional (text)
            position: 'toast-top toast-end',    // optional (daisyUI classes)
        );
    }

    public function render()
    {

        $Select = [
            ['id' => "aktif", 'name' => 'Aktif',],
            ['id' => "tidak", 'name' => 'Tidak',],
        ];

        return view('livewire.whatsapp.pesan', compact('Select'));
    }
}
