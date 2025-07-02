<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

use App\Nasabah,
    App\Models\User,
    App\Transaksi;

class Index extends Component
{

    public $dataPemasukan;
    public $datakeluar;
    public $dataNasaba;

    public function mount()
    {
        $from_date = date('Y-m-d', strtotime('-7 days', strtotime(date("Y-m-d"))));

        $this->dataM($from_date);
        $this->dataK($from_date);
        $this->dataP();
    }

    public function dataM($from_date)
    {



        while (strtotime($from_date) <= strtotime(date("Y-m-d"))) {

            $tanggal[] = $from_date;
            $query = Transaksi::selectRaw('DATE(created_at) as tanggal, sum(jumlah) as total')
                ->where('jenis', 'setor')
                ->whereRaw('DATE(created_at) = "' . $from_date . '"')
                ->groupBy('tanggal')
                ->first();

            $array[] = $query->total ?? 0;






            $from_date = date("Y-m-d", strtotime("+1 day", strtotime($from_date)));
        }


        $dataArray['label'] = 'Tabungan';
        $dataArray['data'] = $array;



        $dataArray['borderColor'] = '#' . substr(md5(rand()), 0, 6);


        $datak[] = $dataArray;
        $datakeluar['datasets'] = $datak;
        $datakeluar['labels'] = $tanggal;
        $this->dataPemasukan = $datakeluar;
    }

    public function dataK($from_date)
    {
        $user = User::get();

        $dataArray = array();
        $datak = array();


        $from_date = date('Y-m-d', strtotime('-7 days', strtotime(date("Y-m-d"))));

        $a = 0;

        foreach ($user as $s) {

            $array = array();
            $tanggal = array();


            while (strtotime($from_date) <= strtotime(date("Y-m-d"))) {

                $tanggal[] = $from_date;
                $query = Transaksi::selectRaw('DATE(created_at) as tanggal, sum(jumlah) as total')
                    ->where('jenis', 'tarik')
                    ->whereRaw('DATE(created_at) = "' . $from_date . '"')
                    ->groupBy('tanggal')
                    ->where('mitra_id', $s->id)
                    ->first();

                $array[] = $query->total ?? 0;






                $from_date = date("Y-m-d", strtotime("+1 day", strtotime($from_date)));
            }


            $dataArray['label'] = $s->name;
            $dataArray['data'] = $array;



            $dataArray['borderColor'] = '#' . substr(md5(rand()), 0, 6);


            $datak[] = $dataArray;

            $from_date = date('Y-m-d', strtotime('-7 days', strtotime(date("Y-m-d"))));
        }




        $datakeluar['datasets'] = $datak;
        $datakeluar['labels'] = $tanggal;
        $this->datakeluar = $datakeluar;
    }

    public function dataP()
    {
        $transaksi = Transaksi::selectRaw('santri_id,DATE(created_at) as tanggal, sum(jumlah) as total')
            ->whereRaw('DATE(created_at) = "' . date("Y-m-d") . '"')
            ->where('jenis', 'tarik')
            ->orderBy('total', 'DESC')
            ->groupBy('santri_id')
            ->with('nasabah')
            ->take(5)
            ->get();

        foreach ($transaksi as $t) {
            $label[] = $t->nasabah->nama;
            $data[] = $t->total;
            $background[] = '#' . substr(md5(rand()), 0, 6);
        }

        $dataNasaba['labels'] = $label ?? '';
        $dataNasaba['datasets']['backgroundColor'] = $background ?? '';
        $dataNasaba['datasets']['data'] = $data ?? '';
        $dataNasaba['datasets']['label'] = 'Grafik Pengeluaran Santri' ?? '';

        $this->dataNasaba = $dataNasaba;
    }

    public function render()
    {
        $nasabah = Nasabah::count();
        $uang = User::select('saldo')->get()->sum("saldo");
        $saldoTabungan = User::select('saldo')->where('id', 1)->get()->sum("saldo");
        $transaksi = Transaksi::whereDate('created_at', DB::raw('CURDATE()'))->count();


        $data = [
            'nasabah' => $nasabah,
            'saldoTabungan' => $saldoTabungan,
            'uang' => $uang,
            'transaksi' => $transaksi,
        ];


        return view('livewire.index', compact('data'));
    }
}
