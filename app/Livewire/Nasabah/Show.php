<?php

namespace App\Livewire\Nasabah;

use Mary\Traits\Toast;
use App\Models\Nasabah;
use Livewire\Component;
use App\Models\Whatsapp;
use App\Models\WhatsappPesan;
use App\Models\Nasabah_transaksi;
use App\Jobs\KirimPesanWhatsappJob;

class Show extends Component
{

    use Toast;


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
        $whatsapp =  Whatsapp::where('transaksi_id', $id)->first();

        $whatsapp->status = 'pending';
        $whatsapp->save();

        KirimPesanWhatsappJob::dispatch($whatsapp);
    }


    public function kirimWa($id)
    {

        $nasabah = $this->nasabah;

        $transaksi = Nasabah_transaksi::find($nasabah->id);
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

        $whatsapp =  Whatsapp::create([
            'nasabah_id' => $nasabah->id,
            'transaksi_id' => $transaksi->id,
            'pesan' => $pesan,
            'status' => 'pending'
        ]);

        KirimPesanWhatsappJob::dispatch($whatsapp);

        $this->success('Pesan WhatsApp berhasil dikirim');
    }

    public function mutasi()
    {
        $nasabah = $this->nasabah;
        $transaksi = Nasabah_transaksi::where('nasabah_id', $nasabah->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $mutasiText = '';

        foreach ($transaksi->reverse() as $item) {
            $tanggal = $item->created_at->format('d-m-Y H:i');

            // Tentukan jenis transaksi
            if ($item->debit > 0) {
                $jenis  = 'Setor Tunai';
                $jumlah = 'Rp. ' . number_format($item->debit, 2, ',', '.');
            } elseif ($item->credit > 0) {
                $jenis  = 'Tarik Tunai';
                $jumlah = 'Rp. ' . number_format($item->credit, 2, ',', '.');
            } else {
                $jenis  = 'Transaksi';
                $jumlah = 'Rp. 0,00';
            }

            $keterangan = $item->keterangan ?? '-';

            $mutasiText .= "Tanggal   : {$tanggal}\n";
            $mutasiText .= "Jenis     : {$jenis}\n";
            $mutasiText .= "Jumlah    : {$jumlah}\n";
            $mutasiText .= "Keterangan: {$keterangan}\n\n";
        }



        // Ambil template pesan mutasi dari tabel whatsapp_pesan
        $wa = WhatsappPesan::where('jenis', 'mutasi')->first();

        $replace = ['{nama}', '{saldo}', '{tanggal}', '{mutasi}'];
        $variable = [
            $nasabah->nama,
            'Rp. ' . number_format($nasabah->saldo, 2, ',', '.'),
            now()->format('d-m-Y H:i'),
            trim($mutasiText),
        ];

        // Replace semua placeholder di template
        $pesan = str_replace($replace, $variable, $wa->pesan);

        $whatsapp =  Whatsapp::create([
            'nasabah_id' => $nasabah->id,
            'transaksi_id' => null,
            'pesan' => $pesan,
            'status' => 'pending'
        ]);

        KirimPesanWhatsappJob::dispatch($whatsapp);

        $this->success('Pesan Mutasi berhasil dikirim');
    }

    public function render()
    {
        return view('livewire.nasabah.show');
    }
}
