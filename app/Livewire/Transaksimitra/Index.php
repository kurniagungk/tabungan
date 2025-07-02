<?php

namespace App\Livewire\Transaksimitra;

use App\Exports\RiwayatExport;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Nasabah;

use App\Models\Transaksi;
use App\Models\User;

class Index extends Component
{
    public $awal;
    public $akhir;
    public $transaksi;


    public function mount()
    {
        $this->awal = date("Y-m-d");
        $this->akhir = date("Y-m-d");
        $this->filter();
    }

    public function filter()
    {

        $this->validate([
            'awal' => 'required|date',
            'akhir' => 'required|date',
        ]);


        $this->transaksi = $this->data()->latest()->get();
    }

    public function data()
    {
        $transaksi = Transaksi::with('mitra', 'nasabah')
            ->whereBetween(DB::raw('DATE(created_at)'), [$this->awal, $this->akhir])
            ->where('mitra_id', Auth::id());

        return $transaksi;
    }

    public function export()
    {
        $this->validate([
            'awal' => 'required|date',
            'akhir' => 'required|date',
        ]);

        $transaksi = $this->data()->latest()->get();


        $data = [
            'transaksi' => $transaksi,
        ];

        Excel::store(new RiwayatExport($data), 'laporan/riwayat.xlsx', 'local');

        $this->dispatch('export');
    }


    public function render()
    {
        return view('livewire.transaksimitra.index');
    }
}
