<?php

namespace App\Http\Livewire\Transaksi;


use Livewire\Component;


class Histori extends Component
{

    public $transaksi;
    public $saldoHistori;

    public function mount($nasabah)
    {
        $transaksi = $nasabah->transaksi;
        $transaksiHistori = $nasabah->transaksi()->orderBy('created_at', 'desc')->take(10)->get();

        $this->transaksi = $transaksiHistori;

        $this->saldoHistori = [
            'debit' => $transaksi->sum('debit') - $transaksiHistori->sum('debit'),
            'credit' => $transaksi->sum('credit') - $transaksiHistori->sum('credit')
        ];
    }



    public function render()
    {
        return view('livewire.transaksi.histori');
    }
}
