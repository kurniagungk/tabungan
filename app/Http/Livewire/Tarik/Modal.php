<?php

namespace App\Http\Livewire\Tarik;

use Livewire\Component;
use App\Santri;


class Modal extends Component
{

    public $password;
    public $search;
    public $santri;

    protected $listeners = ['cek'];


    public function cek($search)
    {

        $santri = Santri::select('password')->where('nis', $search)->first();
        $this->santri = $santri->password;
    }

    public function cekPasword()
    {

        if (!$this->password == $this->santri) {
            $this->addError('password', 'pasword salah');
        } else {
            $this->reset();
            $this->emit('tarik');
        }
    }

    public function render()
    {
        return view('livewire.tarik.modal');
    }
}
