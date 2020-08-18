<?php

namespace App\Http\Livewire\Tarik;

use Livewire\Component;
use Illuminate\Support\Str;
use App\Nasabah, App\Transaksi;

class Index extends Component
{

    public $search;
    public $tarik;
    public $santri;
    public $jumlah;
    public $transaksi;
    public $pasword;
    public $dataTrasaksi;
    public $saldo;
    public $transaksi_id;


    protected $listeners = ['tarik' => 'tarik'];

    public function cari()
    {

        $this->reset('tarik');

        //get santri
        $santri = Nasabah::where('nis', $this->search)->first();
        //

        if ($santri) {
            //get transaksi santri

            $transaksi = Transaksi::where('santri_id', $santri->id)->take(9)->get();

            $total = 0;

            foreach ($transaksi as $t) {

                $tarik = 0;
                $setor = 0;

                if ($t->jenis == 'setor') {
                    $setor = $t->jumlah;
                }
                if ($t->jenis == 'tarik') {
                    $tarik = $t->jumlah;
                }

                $total += $setor - $tarik;
            }

            $data = array();
            $saldoAwal = $santri->saldo - $total;


            $this->saldo = $saldoAwal;

            foreach ($transaksi as $t) {

                $tarik = 0;
                $setor = 0;

                if ($t->jenis == 'setor') {
                    $setor = $t->jumlah;
                }
                if ($t->jenis == 'tarik') {
                    $tarik = $t->jumlah;
                }


                $saldoAwal += $setor - $tarik;

                $dataS = array();
                $dataS['tanggal'] = $t->created_at;
                $dataS['jenis'] = $t->jenis;
                $dataS['setor'] = $setor;
                $dataS['tarik'] = $tarik;
                $dataS['total'] = $saldoAwal;


                $data[] = $dataS;
            }



            //menambah variable ke public
            $total = end($data);

            $this->transaksi = $data;
            $this->santri = $santri;
            $this->transaksi_id = Str::uuid()->toString();

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

        $jumlah = $this->santri->saldo - $this->jumlah;

        $this->santri->update(['saldo' => $jumlah]);

        $this->reset();
        $this->emit('succes');
        session()->flash('pesan', 'setor berhasil dilakukan');
    }


    public function render()
    {
        return view('livewire.tarik.index');
    }
}
