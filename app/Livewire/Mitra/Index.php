<?php

namespace App\Livewire\Mitra;

use Livewire\Component;
use App\Models\User;

class Index extends Component
{

    public $confirming;

    public function confirmDelete($id)
    {
        $this->confirming = $id;
    }

    public function kill($id)
    {
        User::destroy($id);
        session()->flash('pesan', 'Data mitra successfully deleted.');
    }

    public function render()
    {
        $mitra = User::get();
        return view('livewire.mitra.index', compact("mitra"));
    }
}
