<?php

namespace App\Http\Livewire\Dasbord;

use App\Nasabah;
use App\Saldo;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {

        $akhir = date("Y-m-d");
        $awal = date('Y-m-d', strtotime('-30' . ' days'));

        $saldo = Saldo::where('nama', 'tabungan')->first();

        $nasabah = Nasabah::count();





        $data = [
            'jumlahNasaba' => $nasabah,
            'saldo' => $saldo->saldo,
        ];





        return view('livewire.dasbord.index', compact('data'));
    }
}
