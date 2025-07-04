<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use App\Models\Nasabah_transaksi;
use App\Exports\LaporanTransaksi;
use Maatwebsite\Excel\Facades\Excel;


class Transaksi extends Component
{

    public $transaksi;
    public $show = false;
    public $dari, $sampai;


    public function mount()
    {
        $this->dari = date('Y-m-d');
        $this->sampai = date('Y-m-d');
    }

    public function laporan()
    {

        $this->transaksi = null;

        $user = auth()->user();
        $admin = $user->hasRole('admin');

        $transaksi = Nasabah_transaksi::whereBetween('created_at', [$this->dari . ' 00:00:00', $this->sampai . ' 23:59:59'])
            ->withWhereHas('nasabah', function ($query) use ($user, $admin) {
                $query->select('id', 'rekening', 'nama', 'saldo_id')->when(!$admin, function ($query) use ($user) {
                    $query->where('saldo_id', $user->saldo_id);
                });
            })->get();

        $this->transaksi = $transaksi;
        $this->show = true;
    }

    public function updated()
    {
        $this->show = false;
    }

    public function export()
    {
        $data = [
            'transaksi' =>  $this->transaksi,
            'dari' => $this->dari,
            'sampai' => $this->sampai
        ];

        return Excel::download(new LaporanTransaksi($data), 'Laporam Biaya.xlsx');
    }


    public function render()
    {
        return view('livewire.laporan.transaksi');
    }
}
