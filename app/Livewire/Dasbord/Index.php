<?php

namespace App\Livewire\Dasbord;

use App\Models\Nasabah;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {

        $user = auth()->user();
        $admin = $user->hasRole('admin');

        $akhir = date("Y-m-d");
        $awal = date('Y-m-d', strtotime('-30' . ' days'));

        $saldo = Nasabah::when(!$admin, function ($query) use ($user) {
            return $query->where('saldo_id', $user->saldo_id);
        })->sum('saldo');

        $aktif = Nasabah::when(!$admin, function ($query) use ($user) {
            return $query->where('saldo_id', $user->saldo_id);
        })->where('status', 'aktif')->sum('saldo');
        $tidak = Nasabah::when(!$admin, function ($query) use ($user) {
            return $query->where('saldo_id', $user->saldo_id);
        })->where('status', 'tidak')->sum('saldo');



        $nasabah = Nasabah::when(!$admin, function ($query) use ($user) {
            return $query->where('saldo_id', $user->saldo_id);
        })->count();



        $data = [
            'jumlahNasaba' => $nasabah,
            'saldo' => $saldo,
            'aktif' => $aktif,
            'tidak' => $tidak,
        ];

        return view('livewire.dasbord.index', compact('data'));
    }
}
