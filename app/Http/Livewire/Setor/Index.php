<?php

namespace App\Http\Livewire\Setor;

use Livewire\Component;
use Illuminate\Support\Str;
use App\Nasabah, App\Transaksi;


class Index extends Component
{

    public $search;
    public $setor;
    public $santri;
    public $jumlah;
    public $transaksi;
    public $dataTransaksi;
    public $transaksi_id;
    public $saldo;





    public function cari()
    {

        $this->reset('setor');

        //get santri
        $santri = Nasabah::where('nis', $this->search)->first();
        //

        if ($santri) {
            //get transaksi santri

            $transaksi = Transaksi::where('santri_id', $santri->id)->take(10)->get();

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
            $this->setor = true;
            $this->emit('cari');
        }
    }

    public function setor()
    {
        $trasaksi = Transaksi::create([
            'id' => $this->transaksi_id,
            'santri_id' => $this->santri->id,
            'jumlah' => $this->jumlah,
            'jenis' => 1,
        ]);

        $jumlah = $this->santri->saldo + $this->jumlah;



        $this->santri->update(['saldo' => $jumlah]);

        $this->reset();
        $this->emit('setor');
        session()->flash('pesan', 'setor berhasil dilakukan');
    }

    public function render()
    {
        return view('livewire.setor.index');
    }
}
