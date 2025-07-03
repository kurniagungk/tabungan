<?php

namespace App\Livewire\User;

use App\Models\User;
use Mary\Traits\Toast;
use Livewire\Component;
use Livewire\Attributes\Validate;

class Edit extends Component
{
    use Toast;

    public $userId;

    #[Validate('required|min:3')]
    public $name;

    #[Validate()]
    public $email;

    #[Validate('min:6|nullable')]
    public $password;

    protected function rules()
    {
        return [
            'email' => 'required|email|unique:users,email,' . $this->userId,
        ];
    }




    public function mount($id)
    {
        $user = User::find($id);
        $this->name = $user->name;
        $this->email = $user->email;
        $this->userId = $user->id;
    }

    public function simpan()
    {

        $this->validate();

        $user = User::find($this->userId);

        $user->name = $this->name;
        $user->email = $this->email;
        if ($this->password != null)
            $user->password = bcrypt($this->password);
        $user->save();

        $this->toast(
            type: 'success',
            title: 'Berhasil Mengubah Data',

            position: 'toast-top toast-end',    // optional (daisyUI classes)
            icon: 'o-information-circle',       // Optional (any icon)
            css: 'alert-info',                  // Optional (daisyUI classes)
            timeout: 3000,                      // optional (ms)
            redirectTo: "/user"                    // optional (uri)
        );
    }


    public function render()
    {
        return view('livewire.user.edit');
    }
}
