<?php

namespace App\Livewire\Dasbord;

use App\Models\Nasabah;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {

        $akhir = date("Y-m-d");
        $awal = date('Y-m-d', strtotime('-30' . ' days'));

        $saldo = Nasabah::sum('saldo');

        $aktif = Nasabah::where('status', 'aktif')->sum('saldo');
        $tidak = Nasabah::where('status', 'tidak')->sum('saldo');



        $nasabah = Nasabah::count();



        $data = [
            'jumlahNasaba' => $nasabah,
            'saldo' => $saldo,
            'aktif' => $aktif,
            'tidak' => $tidak,
        ];



        return view('livewire.dasbord.index', compact('data'));
    }
}
