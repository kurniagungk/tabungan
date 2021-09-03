<?php

namespace App\Http\Livewire\Transaksi;

use App\Nasabah;
use Livewire\Component;


class Modal extends Component
{


    public $nasabah_id;
    public $nama;
    public $rekening;
    public $password;
    protected $dataNasabah;


    public function mount($rekening)
    {
        $nasabah = Nasabah::select('nama', 'rekening')->find($rekening);
        $this->nama = $nasabah->nama;
        $this->rekening = $nasabah->rekening;
    }


    public function close()
    {
        $this->emit('close');
    }

    public function send()
    {



        $this->validate([
            'password' => 'required'
        ]);


        $nasabah = Nasabah::select('password')->where('rekening', $this->rekening)->first();

        if ($this->password == $nasabah->password) {
            $this->dispatchBrowserEvent('password');
            $this->emit('password');
        } else
            $this->addError('password', 'Password Salah');
    }

    public function render()
    {
        return view('livewire.transaksi.modal');
    }
}
