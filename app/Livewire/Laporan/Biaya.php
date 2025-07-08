<?php

namespace App\Livewire\Laporan;

use App\Models\Nasabah;

use App\Models\Saldo;
use App\Models\Biaya as BiayaModal;
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
    public $lembaga_id;

    public function mount()
    {

        $user = auth()->user();
        $admin = $user->hasRole('admin');
        if (!$admin) {
            $this->lembaga_id = $user->saldo_id;
        }

        $this->tanggal = date('Y-m');
        $this->laporan();
    }

    public function laporan()
    {
        $bulan = substr($this->tanggal, 5);

        $this->cetak = date("Y-m-d H:i:s");
        $nasabahData = Nasabah::where('saldo_id', $this->lembaga_id);

        $saldo = $nasabahData->sum('saldo');
        $nasabah = $nasabahData->count();
        $biaya = BiayaModal::whereMonth('tanggal',  $bulan)->first();

        $this->saldo =  $saldo;
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

        $lembaga = Saldo::get();

        return view('livewire.laporan.biaya', compact('lembaga'));
    }
}
