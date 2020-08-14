<?php

namespace App\Http\Livewire\Mitra;

use Livewire\Component;
use App\Mitra;

class Index extends Component
{
    public function render()
    {
        $mitra = Mitra::get();
        return view('livewire.mitra.index', compact("mitra"));
    }
}
