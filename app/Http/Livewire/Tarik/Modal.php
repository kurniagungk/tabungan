<?php

namespace App\Http\Livewire\Tarik;

use Livewire\Component;
use App\Nasabah;


class Modal extends Component
{

    public $password;
    public $search;
    public $santri;

    protected $listeners = ['cek'];


    public function cek($search)
    {

        $santri = Nasabah::select('password')->where('nis', $search)->first();
        $this->santri = $santri->password;
    }

    public function cekPasword()
    {

        if ($this->password == $this->santri) {
            $this->reset();
            $this->emit('tarik');
        } else {
            $this->addError('password', 'pasword salah');
        }
    }

    public function render()
    {
        return view('livewire.tarik.modal');
    }
}
