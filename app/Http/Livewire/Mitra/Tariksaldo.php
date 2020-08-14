<?php

namespace App\Http\Livewire\Mitra;

use Livewire\Component;

use App\Mitra;

class Tariksaldo extends Component
{
    public function render()
    {
        $mitra = Mitra::get();
        return view('livewire.mitra.tariksaldo', compact("mitra"));
    }
}
