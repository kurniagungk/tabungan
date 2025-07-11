<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class LaporanTransaksiHarian implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $transaksi, $dari, $sampai;

    public function __construct($data)
    {
        $this->transaksi = $data['transaksi'];
        $this->dari = $data['dari'];
        $this->sampai = $data['sampai'];
    }

    public function view(): View
    {



        return view('laporan.transaksi-harian', [
            'transaksi' => $this->transaksi,
            'dari' => $this->dari,
            'sampai' => $this->sampai
        ]);
    }
}
