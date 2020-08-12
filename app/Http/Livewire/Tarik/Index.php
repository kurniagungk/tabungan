<?php

namespace App\Http\Livewire\Tarik;

use Livewire\Component;
use Illuminate\Support\Str;
use App\Santri, App\Transaksi;

class Index extends Component
{

    public $search;
    public $tarik;
    public $santri;
    public $jumlah;
    public $transaksi;
    public $pasword;


    protected $listeners = ['tarik' => 'tarik'];

    public function cari()
    {


        $this->reset('tarik');

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
            $this->tarik = true;
            $this->emit('cari');
        }
    }

    public  function cek()
    {
        $validatedData = $this->validate([
            'jumlah' => 'required|integer',
        ]);
        $jumlah = $this->santri->saldo - $this->jumlah;

        if ($jumlah >= 0 && $this->santri->saldo > 0) {
            $this->emit('cek', $this->search);
        } else {
            $this->addError('jumlah', 'saldo tidak mencukupi');
        }
    }

    public function tarik()
    {

        $validatedData = $this->validate([
            'jumlah' => 'required|integer',
        ]);
        $jumlah = $this->santri->saldo - $this->jumlah;


        if ($jumlah >= 0 && $this->santri->saldo > 0) {
            $this->emit('cek', $this->search);
        } else {
            $this->addError('jumlah', 'saldo tidak mencukupi');
        }


        $trasaksi = Transaksi::create([
            'id' => Str::uuid(),
            'santri_id' => $this->santri->id,
            'jumlah' => $this->jumlah,
            'jenis' => 2,
        ]);


        $this->santri->update(['saldo' => $jumlah]);

        $this->reset('tarik');
        $this->emit('succes');
        session()->flash('pesan', 'setor berhasil dilakukan');
    }


    public function render()
    {
        return view('livewire.tarik.index');
    }
}
