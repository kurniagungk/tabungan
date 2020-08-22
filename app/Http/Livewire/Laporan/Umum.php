<?php

namespace App\Http\Livewire\Laporan;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

use App\Mitra;
use App\Transaksi;

class Umum extends Component
{

    public $awal;
    public $akhir;
    public $selectMitra;
    public $jenisTransaksi = '';
    public $transaksi;


    public function filter()
    {

        $this->validate([
            'awal' => 'required|date',
            'akhir' => 'required|date',
        ]);

        $transaksi = Transaksi::with('mitra', 'nasabah')
            ->whereBetween(DB::raw('DATE(created_at)'), [$this->awal, $this->akhir]);
        if ($this->selectMitra)
            $transaksi->where('mitra_id', $this->selectMitra);
        if ($this->jenisTransaksi)
            $transaksi->where('jenis', $this->jenisTransaksi);


        $this->transaksi = $transaksi->latest()->get();
    }


    public function render()
    {
        $mitra = Mitra::latest()->get();
        return view('livewire.laporan.umum', compact('mitra'));
    }
}
