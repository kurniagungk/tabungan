<?php

namespace App\Http\Livewire\Mitra;

use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


use App\Mitra;

class Edit extends Component
{

    public $email;
    public $nama;
    public $password;
    public $repassword;
    public $mitraId;


    public function mount(Mitra $mitra)
    {
        $this->mitraId = $mitra->id;
        $this->nama = $mitra->nama;
        $this->email = $mitra->email;
    }

    public function update()
    {

        $validatedData = Validator::make(
            ['email' => $this->email],
            ['email' => Rule::unique('mitra')->ignore($this->mitraId)],
        )->validate();

        $this->validate([
            'nama' => 'required|min:6',
            'password' => 'required|min:6',
            'repassword' => 'required|same:password',
        ]);

        Mitra::where('id', $this->mitraId)
            ->update([
                'nama' => $this->nama,
                'email' => $this->email,
                'password' => $this->password,
            ]);

        session()->flash('pesan', 'Mitra successfully Update.');
        return redirect()->to('/mitra');
    }

    public function render()
    {
        return view('livewire.mitra.edit');
    }
}
