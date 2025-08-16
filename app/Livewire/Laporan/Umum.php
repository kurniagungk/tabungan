<?php

namespace App\Livewire\Laporan;

use App\Exports\LaporanUmum;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Transaksi;
use App\Models\User;

class Umum extends Component
{

    public $awal;
    public $akhir;
    public $selectMitra;
    public $jenisTransaksi = '';
    public $transaksi;
    public $totalSetor;
    public $totalTarik;


    public function filter()
    {

        $this->validate([
            'awal' => 'required|date',
            'akhir' => 'required|date',
        ]);


        $this->totalSetor = $this->data()->where('jenis', 'setor')->sum('jumlah');
        $this->totalTarik = $this->data()->where('jenis', 'tarik')->sum('jumlah');

        $this->transaksi = $this->data()->latest()->get();
    }

    public function data()
    {
        $transaksi = Transaksi::with('mitra', 'nasabah')
            ->whereBetween(DB::raw('DATE(created_at)'), [$this->awal, $this->akhir]);
        if ($this->selectMitra)
            $transaksi->where('mitra_id', $this->selectMitra);
        if ($this->jenisTransaksi)
            $transaksi->where('jenis', $this->jenisTransaksi);

        return $transaksi;
    }

    public function export()
    {
        $this->validate([
            'awal' => 'required|date',
            'akhir' => 'required|date',
        ]);

        $transaksi = $this->data()->latest()->get();
        $awal = $this->awal;
        $akhir = $this->akhir;
        $totalSetor = $this->data()->where('jenis', 'setor')->sum('jumlah');
        $totalTarik = $this->data()->where('jenis', 'tarik')->sum('jumlah');

        $data = [
            'transaksi' => $transaksi,
            'awal' => $awal,
            'akhir' => $akhir,
            'setor' => $totalSetor,
            'tarik' => $totalTarik
        ];

        Excel::store(new LaporanUmum($data), 'laporan/laporan-umum.xlsx', 'local');

        $this->dispatch('export');
    }


    public function render()
    {
        $mitra = User::latest()->get();

        $headers = [
            ['key' => 'no', 'label' => 'No'],
            ['key' => 'id', 'label' => 'ID Transaksi'],
            ['key' => 'created_at', 'label' => 'Tanggal'],
            ['key' => 'nasabah.nis', 'label' => 'Rekening'],
            ['key' => 'nasabah.nama', 'label' => 'Nama'],
            ['key' => 'jenis', 'label' => 'Jenis'],
            ['key' => 'mitra.name', 'label' => 'Sumber'],
            ['key' => 'jumlah', 'label' => 'Jumlah'],
        ];
        return view('livewire.laporan.umum', compact('mitra', 'headers'));
    }
}
