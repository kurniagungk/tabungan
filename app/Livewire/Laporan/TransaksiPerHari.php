<?php

namespace App\Livewire\Laporan;

use App\Models\Saldo;
use Livewire\Component;
use Illuminate\Support\Carbon;
use App\Exports\LaporanTransaksi;
use App\Models\Nasabah_transaksi;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanTransaksiHarian;
use Illuminate\Database\Eloquent\Builder;

class TransaksiPerHari extends Component
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


        $transaksi = DB::table('nasabah_transaksi')
            ->selectRaw('SUM(debit) as setor, SUM(credit) as tarik, DATE(created_at) as day')
            ->whereBetween('created_at', [$this->dari . ' 00:00:00', $this->sampai . ' 23:59:59'])
            ->whereExists(function ($query) use ($lembaga_id) {
                $query->select(DB::raw(1))
                    ->from('nasabah')
                    ->whereColumn('nasabah.id', 'nasabah_transaksi.nasabah_id')
                    ->where('saldo_id', $lembaga_id);
            })
            ->groupByRaw('DATE(created_at)')
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $start = Carbon::parse($this->dari);
        $end = Carbon::parse($this->sampai);

        $result = collect();
        while ($start->lte($end)) {
            $tanggal = $start->format('Y-m-d');
            if ($transaksi->has($tanggal)) {
                $result->put($tanggal, $transaksi->get($tanggal));
            } else {
                $result->put($tanggal, (object)[
                    'setor' => 0,
                    'tarik' => 0,
                    'day' => $tanggal,
                ]);
            }
            $start->addDay();
        }

        $this->transaksi = $result;
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

        return Excel::download(new LaporanTransaksiHarian($data), 'Laporam Transaksi Per Hari' . date('Y-m-d H:i') . '.xlsx');
    }



    public function render()
    {
        $lembaga = Saldo::get()->prepend((object)[
            'id' => '',
            'nama' => 'Pilih Lembaga'
        ]);


        return view('livewire.laporan.transaksi-per-hari', compact('lembaga'));
    }
}
