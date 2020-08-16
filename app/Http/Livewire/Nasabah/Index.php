<?php

namespace App\Http\Livewire\Nasabah;

use Livewire\Component;

use App\Nasabah;

class Index extends Component
{
    public $confirming;

    public function confirmDelete($id)
    {
        $this->confirming = $id;
    }

    public function kill($id)
    {
        Nasabah::destroy($id);
        session()->flash('pesan', 'Data mitra successfully deleted.');
    }


    public function render()
    {
        $nasabah = Nasabah::get();

        return view('livewire.nasabah.index', compact("nasabah"));
    }
}
