<?php

namespace App\Http\Livewire\Mitra;

use Livewire\Component;
use Illuminate\Support\Str;


use App\User;

class Create extends Component
{

    public $email;
    public $nama;
    public $password;
    public $repassword;

    public function store()
    {
        $this->validate([
            'nama' => 'required|min:6',
            'email' => 'required|email|unique:mitra,email',
            'password' => 'required|min:6',
            'repassword' => 'required|same:password',
        ]);

        $mitra = User::create([
            'name' => $this->nama,
            'email' => $this->email,
            'password' => $this->password,
        ]);

        $mitra->assignRole('mitra');

        session()->flash('pesan', 'Mitra successfully Created.');
        return redirect()->to('/mitra');
    }


    public function render()
    {
        return view('livewire.mitra.create');
    }
}
