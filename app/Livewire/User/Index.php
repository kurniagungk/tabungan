<?php

namespace App\Livewire\User;

use App\Models\User;
use Mary\Traits\Toast;
use Livewire\Component;
use Livewire\WithPagination;
use Nette\Utils\Random;

class Index extends Component
{

    use Toast;

    public $perPage = 5;

    use WithPagination;


    public function delete($id)
    {

        $user = User::find($id);
        $user->password = bcrypt(str()->random(8));
        $user->save();
        $user->delete();

        $this->toast(
            type: 'success',
            title: 'Berhasil Hapus Data',
        );
    }

    public function render()
    {

        $user = auth()->user();
        $admin = $user->hasRole('admin');


        $users = User::when(!$admin, function ($query) use ($user) {
            return $query->where('saldo_id', $user->saldo_id);
        })->with('saldo:id,nama', 'roles')->paginate($this->perPage);

        $headers = [
            ['key' => 'name', 'label' => 'Nama'],
            ['key' => 'email', 'label' => 'E-mail'],
            ['key' => 'saldo.nama', 'label' => 'Lembaga'],
            ['key' => 'peran', 'label' => 'Peran']
        ];


        return view('livewire.user.index', compact('users', 'headers'));
    }
}
