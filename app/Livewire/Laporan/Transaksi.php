<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use App\Models\Nasabah_transaksi;
use App\Exports\LaporanTransaksi;
use Maatwebsite\Excel\Facades\Excel;


class Transaksi extends Component
{

    public $transaksi;
    public $show = false;
    public $dari, $sampai;


    public function mount()
    {
        $this->dari = date('Y-m-d');
        $this->sampai = date('Y-m-d');
    }

    public function laporan()
    {

        $this->transaksi = null;

        $transaksi = Nasabah_transaksi::whereBetween('created_at', [$this->dari . ' 00:00:00', $this->sampai . ' 23:59:59'])->with('nasabah')->get();

        $this->transaksi = $transaksi;
        $this->show = true;
    }

    public function updated()
    {
        $this->show = false;
    }

    public function export()
    {
        $data = [
            'transaksi' =>  $this->transaksi,
            'dari' => $this->dari,
            'sampai' => $this->sampai
        ];

        return Excel::download(new LaporanTransaksi($data), 'Laporam Biaya.xlsx');
    }


    public function render()
    {
        return view('livewire.laporan.transaksi');
    }
}
