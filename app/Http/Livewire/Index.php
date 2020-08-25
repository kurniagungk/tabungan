<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

use App\Mitra,
    App\Nasabah,
    App\Transaksi;

class Index extends Component
{


    public function render()
    {
        $nasabah = Nasabah::count();
        $uang = Mitra::select('saldo')->get()->sum("saldo");
        $saldoTabungan = Mitra::select('saldo')->where('id', 1)->get()->sum("saldo");
        $transaksi = Transaksi::whereDate('created_at', DB::raw('CURDATE()'))->count();

        $data = [
            'nasabah' => $nasabah,
            'saldoTabungan' => $saldoTabungan,
            'uang' => $uang,
            'transaksi' => $transaksi
        ];

        return view('livewire.index', compact('data'));
    }
}
