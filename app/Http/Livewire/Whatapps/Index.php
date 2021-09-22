<?php

namespace App\Http\Livewire\Whatapps;

use App\Setting;
use App\Whatapps;
use Livewire\Component;

class Index extends Component
{


    public $status  = false;
    public $io  = false;
    public $whatapps  = false;

    protected $listeners = ['pesan'];


    public function mount()
    {
        $this->pesan();
    }

    public function pesan()
    {
        $this->whatapps = Whatapps::orderBy('created_at', 'desc')->take(10)->get();
    }

    public function render()
    {
        $url = Setting::where('nama', 'socker_io')->first();

        return view('livewire.whatapps.index', compact('url'));
    }
}
