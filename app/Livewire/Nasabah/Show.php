<?php

namespace App\Livewire\Nasabah;

use App\Models\Nasabah;
use App\Models\Nasabah_transaksi;
use Livewire\Component;
use App\Models\Whatsapp;
use App\Models\WhatsappPesan;

class Show extends Component
{


    public $nasabah;
    public $transaksi;
    public $saldoHistori;

    public function mount(Nasabah $nasabah)
    {
        $this->nasabah = $nasabah;
        $transaksi = $nasabah->transaksi;
        $transaksiHistori = $nasabah->transaksi()->with('whatsapp')->orderBy('created_at', 'desc')->take(10)->get();

        $this->transaksi = $transaksiHistori;


        $this->saldoHistori = [
            'debit' => $transaksi->sum('debit') - $transaksiHistori->sum('debit'),
            'credit' => $transaksi->sum('credit') - $transaksiHistori->sum('credit')
        ];
    }

    public function ulangi($id)
    {
        Whatsapp::where('transaksi_id', $id)->update(['status' => 'pending']);
    }


    public function kirimWa($id)
    {



        $transaksi = Nasabah_transaksi::find($id);
        $nasabah = $transaksi->nasabah;

        $jenis = $transaksi->debit > 0 ? 'tarik' : 'setor';

        $jumlah = $transaksi->debit > 0 ? $transaksi->debit : $transaksi->credit;

        $wa = WhatsappPesan::where('jenis', $jenis)->first();

        if (!$wa || $wa->status == "tidak")
            return;

        $replace = ['{nama}', '{saldo}', '{jumlah}', '{tanggal}', '{keterangan}'];
        $variable = [
            $nasabah->nama,
            'Rp. ' . number_format($nasabah->saldo, 2, ',', '.'),
            'Rp. ' . number_format($jumlah, 2, ',', '.'),
            date('d-m-Y H:i', strtotime($transaksi->created_at)),
            $transaksi->keterangan ?: '-'

        ];
        $pesan = str_replace($replace, $variable, $wa->pesan);

        Whatsapp::create([
            'nasabah_id' => $nasabah->id,
            'transaksi_id' => $transaksi->id,
            'pesan' => $pesan,
            'status' => 'pending'
        ]);
    }

    public function render()
    {
        return view('livewire.nasabah.show');
    }
}
