<?php

namespace App\Livewire\Dasbord;

use App\Models\User;
use App\Models\Saldo;
use App\Models\Warna;
use App\Models\Nasabah;
use Livewire\Component;
use App\Models\Nasabah_transaksi;
use Illuminate\Support\Facades\DB;

/**
 * Komponen Livewire untuk menampilkan chart/grafik pada dashboard.
 *
 * Komponen ini berisi logika untuk mengambil data transaksi dan data nasabah,
 * kemudian memformat data tersebut agar dapat digunakan oleh library charting
 * di sisi client (JavaScript). Komponen ini menggunakan event dispatching
 * untuk mengirimkan data chart ke view.
 */
class Chart extends Component
{
    /**
     * Mengambil dan memproses data transaksi untuk chart.
     *
     * Query data transaksi (setor dan tarik) dari tabel `nasabah_transaksi` dalam rentang waktu 30 hari terakhir.
     * Data difilter berdasarkan user yang login (jika bukan admin) dan status nasabah ('aktif').
     * Data dikelompokkan berdasarkan tanggal.
     *
     * Data yang dihasilkan kemudian diformat menjadi array yang sesuai dengan format yang dibutuhkan
     * oleh library charting di sisi client, dan dikirimkan melalui event `chart-transaksi`.
     *
     * @return void
     */
    public function transaksi()
    {
        // Mendapatkan user yang sedang login.
        $user = auth()->user();

        // Memeriksa apakah user memiliki role 'admin'.
        $admin = $user->hasRole('admin');

        // Mendapatkan saldo_id user (untuk filter data jika bukan admin).
        $saldo = User::select("id", "saldo_id")->where('saldo_id', $user->saldo_id)->get();

        // Menentukan rentang tanggal (30 hari terakhir).
        $akhir = date('Y-m-d', strtotime('+1 days'));
        $awal = date('Y-m-d', strtotime('-30 days'));

        // Query data transaksi.
        $transaksi = Nasabah_transaksi::select(DB::raw('sum(debit) as setor, sum(credit) as tarik, date(created_at) as day'))
            ->whereBetween('created_at', [$awal, $akhir])
            ->groupBy('day')
            ->when(!$admin, function ($query) use ($saldo) {
                return $query->whereIn('user_id', $saldo->pluck('id'));
            })
            ->whereHas('nasabah', function ($query) use ($user) {
                return $query->where('status', 'aktif');
            })
            ->orderBy('day')
            ->get();

        // Memformat data transaksi.
        $labels = [];
        $dataSetor = [];
        $dataTarik = [];

        foreach ($transaksi as $tr) {
            $labels[] = $tr->day;
            $dataSetor[] = (float) $tr->setor;
            $dataTarik[] = (float) $tr->tarik;
        }

        // Mengambil data warna secara random (tidak digunakan dalam data chart).
        $warna = Warna::inRandomOrder()->limit(2)->get();

        // Membuat array data chart.
        $data = [
            'series' => [
                [
                    'name' => 'Setor',
                    'data' => $dataSetor,
                    'color' => 'oklch(79.2% 0.209 151.711)', // Warna langsung dalam format OKLCH
                ],
                [
                    'name' => 'Tarik',
                    'data' => $dataTarik,
                    'color' => 'oklch(71% 0.194 13.428)', // Warna langsung dalam format OKLCH
                ],
            ],
            'labels' => $labels,
        ];

        // Mengirimkan data chart melalui event 'chart-transaksi'.
        $this->dispatch('chart-transaksi', chart: $data);
    }

    /**
     * Mengambil dan memproses data nasabah per tahun untuk chart.
     *
     * Query data nasabah (tahun dan jumlah saldo) dari tabel `nasabah`.
     * Data dikelompokkan berdasarkan tahun dan diurutkan secara ascending.
     *
     * Data yang dihasilkan kemudian diformat menjadi array yang sesuai dengan format yang dibutuhkan
     * oleh library charting di sisi client.
     *
     * @return array
     */
    public function nasabah()
    {
        // Query data nasabah.
        $nasabah = Nasabah::select(DB::raw("tahun, SUM(saldo) as jumlah"))->orderBy('tahun', 'asc')->groupBy("tahun")->get();

        // Memformat data nasabah.
        $data['labels'] = $nasabah->pluck('tahun');
        $data['data'] = $nasabah->pluck('jumlah');

        // Mengambil data warna secara random.
        $warna = Warna::inRandomOrder()->limit(count($nasabah))->get();

        // Menambahkan warna ke array data.
        foreach ($warna as $s) {
            $data['backgroundColor'][] = '#' . $s->code;
        }

        return $data;
    }

    /**
     * Merender view komponen.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('livewire.dasbord.chart');
    }
}
