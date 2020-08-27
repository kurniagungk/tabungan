<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

use App\Nasabah,
    App\User,
    App\Transaksi;

class Index extends Component
{


    public function render()
    {
        $nasabah = Nasabah::count();
        $uang = User::select('saldo')->get()->sum("saldo");
        $saldoTabungan = User::select('saldo')->where('id', 1)->get()->sum("saldo");
        $transaksi = Transaksi::whereDate('created_at', DB::raw('CURDATE()'))->count();

        $dataChart = Transaksi::selectRaw('DATE(created_at) as tanggal, sum(jumlah) as total')
            ->whereDate('jenis', 'setor')
            ->groupBy('tanggal')
            ->get();

        $tanggal = [];
        $total = [];
        foreach ($dataChart as $d) {
            $tanggal[] = $d->tanggal;
            $total[] = $d->total;
        }

        $data = [
            'nasabah' => $nasabah,
            'saldoTabungan' => $saldoTabungan,
            'uang' => $uang,
            'transaksi' => $transaksi,
        ];
        $dataChart = [
            'tanggal' => $tanggal,
            'total' => $total
        ];

        return view('livewire.index', compact('data', 'dataChart'));
    }
}
