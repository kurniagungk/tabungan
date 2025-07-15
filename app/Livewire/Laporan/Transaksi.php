<?php

namespace App\Livewire\Laporan;

use App\Models\Saldo;
use Livewire\Component;
use App\Exports\LaporanTransaksi;
use App\Models\Nasabah_transaksi;
use Livewire\Attributes\Validate;
use Maatwebsite\Excel\Facades\Excel;


class Transaksi extends Component
{

    public $transaksi;
    public $show = false;
    public $dari, $sampai;

    #[Validate('required')]
    public $lembaga_id;


    public function mount()
    {
        $this->dari = date('Y-m-d');
        $this->sampai = date('Y-m-d');



        $user = auth()->user();
        $admin = $user->hasRole('admin');
        if (!$admin) {
            $this->lembaga_id = $user->saldo_id;
        }
    }

    public function laporan()
    {

        $this->validate();

        $start = \Carbon\Carbon::parse($this->dari);
        $end = \Carbon\Carbon::parse($this->sampai);

        if ($start->diffInDays($end) > 30) {
            return  $this->addError('sampai', 'Rentang tanggal tidak boleh lebih dari 30 hari.');
        }

        $this->transaksi = null;

        $lembaga_id = $this->lembaga_id;

        $transaksi = Nasabah_transaksi::whereBetween('created_at', [$this->dari . ' 00:00:00', $this->sampai . ' 23:59:59'])
            ->withWhereHas('nasabah', function ($query) use ($lembaga_id) {
                $query->select('id', 'rekening', 'nama', 'saldo_id')
                    ->where('saldo_id', $lembaga_id)
                    ->where('status', 'aktif');
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

        return Excel::download(new LaporanTransaksi($data), 'Laporam Transaksi' . date('Y-m-d H:i') . '.xlsx');
    }


    public function render()
    {
        $lembaga = Saldo::get()->prepend((object)[
            'id' => '',
            'nama' => 'Pilih Lembaga'
        ]);

        return view('livewire.laporan.transaksi', compact('lembaga'));
    }
}
