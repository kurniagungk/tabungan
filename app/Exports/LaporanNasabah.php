<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class LaporanNasabah implements FromView
{
    private $transaksi, $tanggal_dari, $tanggal_sampai, $nasabah;

    public function __construct($data)
    {
        $this->transaksi = $data['transaksi'];
        $this->tanggal_dari = $data['tanggal_dari'];
        $this->tanggal_sampai = $data['tanggal_sampai'];
        $this->nasabah = $data['nasabah'];
    }

    public function view(): View
    {
        return view('laporan.nasabah', [
            'transaksi' => $this->transaksi,
            'tanggal_dari' => $this->tanggal_dari,
            'tanggal_sampai' => $this->tanggal_sampai,
            'nasabah' => $this->nasabah
        ]);
    }
}
