<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;


use App\User;

class Account extends Component
{

    public $name;
    public $email;
    public $OldPassword = '';
    public $NewPassword;
    public $NewPasswordVerifikasi;
    public $AdminId;

    public function mount()
    {
        $admin = Auth::user();
        $this->name = $admin->name;
        $this->email = $admin->email;
        $this->AdminId = $admin->id;
    }

    public function Update()
    {

        $validatedData = $this->validate([
            'name' => 'required',
            'email' => ['required', Rule::unique('users')->ignore($this->AdminId),],
            'OldPassword' => 'password:web',
            'NewPassword' => 'required||min:6',
            'NewPasswordVerifikasi' => 'same:NewPassword'
        ]);

        $admin = User::find($this->AdminId);

        $admin->name = $this->name;
        $admin->email = $this->email;
        $admin->password =  \Hash::make($this->NewPassword);
        $admin->save();
        session()->flash('status', 'Admins successfully Update.');
    }


    public function render()
    {
        return view('livewire.admin.account');
    }
}
