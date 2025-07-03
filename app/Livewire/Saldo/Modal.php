<?php

namespace App\Livewire\Saldo;

use App\Models\Saldo;
use Livewire\Component;

class Modal extends Component
{


    public $saldoId;
    public $nama;


    public function mount($id)
    {
        $this->saldoId = $id;
        $this->nama = Saldo::find($id)?->nama;
    }




    public function render()
    {
        return view('livewire.saldo.modal');
    }
}
