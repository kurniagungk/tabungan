<?php

namespace App\Http\Livewire\Laporan;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Exports\LaporanMitra;
use Maatwebsite\Excel\Facades\Excel;

use App\User;
use App\Jurnal;

class Mitra extends Component
{

    public $awal;
    public $akhir;
    public $selectMitra;
    public $jurnal;
    public $totalTarik;


    public function filter()
    {

        $this->validate([
            'awal' => 'required|date',
            'akhir' => 'required|date',
        ]);

        $jurnal = $this->data()->latest()->get();

        $this->totalTarik = $jurnal->sum('jumlah');

        $this->jurnal = $jurnal;
    }

    public function data()
    {
        $jurnal = Jurnal::with('mitra')
            ->whereBetween(DB::raw('DATE(created_at)'), [$this->awal, $this->akhir]);

        return $jurnal;
    }

    public function export()
    {
        $this->validate([
            'awal' => 'required|date',
            'akhir' => 'required|date',
        ]);

        $jurnal = $this->data()->latest()->get();
        $awal = $this->awal;
        $akhir = $this->akhir;

        $totalTarik = $jurnal->sum('jumlah');

        $data = [
            'jurnal' => $jurnal,
            'awal' => $awal,
            'akhir' => $akhir,
            'tarik' => $totalTarik,
        ];

        Excel::store(new LaporanMitra($data), 'laporan/mitra.xlsx', 'local');

        $this->emit('export');
    }


    public function render()
    {
        $mitra = User::latest()->get();
        return view('livewire.laporan.mitra', compact('mitra'));
    }
}
