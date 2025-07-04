<?php

namespace App\Livewire\Nasabah;

use App\Models\Nasabah;

use Livewire\Component;

class Show extends Component
{


    public $nasabah;

    public function mount(Nasabah $nasabah)
    {
        $this->nasabah = $nasabah;
    }

    public function render()
    {
        return view('livewire.nasabah.show');
    }
}
