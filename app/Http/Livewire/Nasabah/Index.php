<?php

namespace App\Http\Livewire\Nasabah;

use Livewire\Component;

use App\Nasabah;

class Index extends Component
{
    public function render()
    {
        $nasabah = Nasabah::get();

        return view('livewire.nasabah.index', compact("nasabah"));
    }
}
