<?php

namespace App\Http\Livewire\Mitra;

use Livewire\Component;
use Illuminate\Validation\Rule;



use App\User;

class Edit extends Component
{

    public $email;
    public $nama;
    public $password;
    public $repassword;
    public $mitraId;


    public function mount(User $mitra)
    {
        $this->mitraId = $mitra->id;
        $this->nama = $mitra->name;
        $this->email = $mitra->email;
    }

    public function update()
    {


        $this->validate([
            'email' => ['required', Rule::unique('mitra')->ignore($this->mitraId, 'email')],
            'nama' => 'required|min:6',
            'password' => 'required|min:6',
            'repassword' => 'required|same:password',
        ]);

        User::where('id', $this->mitraId)
            ->update([
                'name' => $this->nama,
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
