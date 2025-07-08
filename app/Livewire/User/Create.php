<?php

namespace App\Livewire\User;

use App\Models\User;
use App\Models\Saldo;
use Mary\Traits\Toast;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Spatie\Permission\Models\Role;

class Create extends Component
{

    use Toast;

    #[Validate('required|min:3')]
    public $name;

    #[Validate('required|email|unique:users,email')]
    public $email;

    #[Validate('required|min:6')]
    public $password;

    #[Validate('required|numeric')]
    public $saldo_id = null;

    #[Validate('required|numeric')]
    public $role_id = null;

    public function simpan()
    {



        $user = auth()->user();
        $admin = $user->hasRole('admin');

        if (!$admin) {
            $this->saldo_id = $user->saldo_id;
            $this->role_id = Role::where('name', 'petugas')->first()->id;
        }


        $this->validate();

        $save =   User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'saldo_id' => $this->saldo_id,
        ]);

        $role = Role::find($this->role_id);

        $save->assignRole($role->name);

        $this->toast(
            type: 'success',
            title: 'Berhasil Menambahkan Data',

            position: 'toast-top toast-end',    // optional (daisyUI classes)
            icon: 'o-information-circle',       // Optional (any icon)
            css: 'alert-info',                  // Optional (daisyUI classes)
            timeout: 3000,                      // optional (ms)
            redirectTo: "/user"                    // optional (uri)
        );
    }


    public function render()
    {
        $saldo = Saldo::all()->prepend((object)[
            'id' => '',
            'nama' => 'Pilih Saldo'
        ]);

        $role = Role::all()->prepend((object)[
            'id' => '',
            'nama' => 'Pilih Saldo'
        ]);

        return view('livewire.user.create', compact('saldo', 'role'));
    }
}
