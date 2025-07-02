<?php

namespace App\Livewire\Laporan;

use App\Models\Nasabah;

use App\Models\Saldo;
use App\Biaya as BiayaModal;
use App\Exports\LaporanBiaya;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\Component;

class Biaya extends Component
{


    public $transaksi;
    public $tanggal;
    public $cetak;
    public $saldo;
    public $nasabah;
    public $biaya;

    public function mount()
    {
        $this->tanggal = date('Y-m');
        $this->laporan();
    }

    public function laporan()
    {
        $bulan = substr($this->tanggal, 5);

        $this->cetak = date("Y-m-d H:i:s");
        $saldo = Saldo::where('nama', 'tabungan')->first();
        $nasabah = Nasabah::count();
        $biaya = BiayaModal::whereMonth('tanggal',  $bulan)->first();
        $this->saldo =  $saldo->saldo;
        $this->nasabah =  $nasabah;
        $this->biaya =  $biaya?->jumlah;
    }

    public function export()
    {
        $data = [
            'saldo' =>  $this->saldo,
            'nasabah' => $this->nasabah,
            'biaya' => $this->biaya,
            'bulan' => $this->tanggal,
        ];
        return Excel::download(new LaporanBiaya($data), 'Laporam Biaya ' . $this->tanggal . '.xlsx');
    }

    public function render()
    {
        return view('livewire.laporan.biaya');
    }
}
