<?php

namespace App\Livewire\Dasbord;

use App\Models\Nasabah;

use App\Models\Nasabah_transaksi;
use App\Warna;
use Livewire\Component;
use Illuminate\Support\Facades\DB;


class Chart extends Component
{





    public function transaksi()
    {
        $akhir = date('Y-m-d', strtotime('+1 days'));
        $awal = date('Y-m-d', strtotime('-30 days'));

        $transaksi = Nasabah_transaksi::select(DB::raw('sum(debit) as setor, sum(credit) as tarik, date(created_at) as day'))
            ->whereBetween('updated_at', [$awal, $akhir])
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $labels = [];
        $dataSetor = [];
        $dataTarik = [];

        foreach ($transaksi as $tr) {
            $labels[] = $tr->day;
            $dataSetor[] = (float) $tr->setor;
            $dataTarik[] = (float) $tr->tarik;
        }

        $warna = warna::inRandomOrder()->limit(2)->get();

        $data = [
            'series' => [
                [
                    'name' => 'Setor',
                    'data' => $dataSetor,
                    'color' => 'oklch(79.2% 0.209 151.711)', // langsung string OKLCH
                ],
                [
                    'name' => 'Tarik',
                    'data' => $dataTarik,
                    'color' => 'oklch(71% 0.194 13.428)', // langsung string OKLCH
                ],
            ],
            'labels' => $labels,
        ];

        $this->dispatch('chart-transaksi', chart: $data);
    }


    public function nasabah()
    {
        $nasabah = Nasabah::select(DB::raw("tahun, SUM(saldo) as jumlah"))->orderBy('tahun', 'asc')->groupBy("tahun")->get();


        $data['labels'] = $nasabah->pluck('tahun');
        $data['data'] = $nasabah->pluck('jumlah');


        $warna = Warna::inRandomOrder()->limit(count($nasabah))->get();

        foreach ($warna as $s) {
            $data['backgroundColor'][] = '#' . $s->code;
        }

        return $data;
    }




    public function render()
    {
        return view('livewire.dasbord.chart');
    }
}
