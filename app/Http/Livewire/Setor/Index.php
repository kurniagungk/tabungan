<?php

namespace App\Http\Livewire\Setor;

use Livewire\Component;
use Illuminate\Support\Str;
use App\Santri, App\Transaksi;


class Index extends Component
{

    public $search;
    public $setor;
    public $santri;
    public $jumlah;
    public $transaksi;


    public function cari()
    {
        $this->reset('setor');

        //get santri
        $santri = Santri::where('nis', $this->search)->first();
        //

        if ($santri) {
            //get transaksi santri

            $trasaksi = Transaksi::where('santri_id', $santri->id)->get();


            //menambah variable ke public

            $this->transaksi = $trasaksi;
            $this->santri = $santri;

            //membuka setor
            $this->setor = true;
        }
    }

    public function setor()
    {
        $trasaksi = Transaksi::create([
            'id' => Str::uuid(),
            'santri_id' => $this->santri->id,
            'jumlah' => $this->jumlah,
            'jenis' => 1,
        ]);

        $jumlah = $this->santri->saldo + $this->jumlah;



        $this->santri->update(['saldo' => $jumlah]);

        $this->reset('setor');
        session()->flash('pesan', 'setor berhasil dilakukan');
    }

    public function render()
    {
        return view('livewire.setor.index');
    }
}
