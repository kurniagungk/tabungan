<?php

namespace App\Livewire\Laporan;

use Livewire\Component;
use Livewire\Attributes\Url;
use App\Exports\LaporanNasabah;
use App\Models\Nasabah_transaksi;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Nasabah as ModelsNasabah;

class Nasabah extends Component
{
    #[Url]
    public $rekening = '';

    public $tanggal_dari;
    public $tanggal_sampai;
    public $nasabah = null;
    public $transaksi = [];
    public $totalSetor = 0;
    public $totalTarik = 0;
    public $lembaga_id;

    public function mount()
    {
        $this->tanggal_dari = date('Y-m-d', strtotime('-30 days'));
        $this->tanggal_sampai = date('Y-m-d');


        $user = auth()->user();
        $admin = $user->hasRole('admin');
        if (!$admin) {
            $this->lembaga_id = $user->saldo_id;
        }

        if (!empty($this->rekening)) {
            $this->filter();
        }
    }

    public function filter()
    {
        $this->validate([
            'tanggal_dari' => 'required|date',
            'tanggal_sampai' => 'required|date',
            'rekening' => 'required'
        ]);

        $start = \Carbon\Carbon::parse($this->tanggal_dari);
        $end = \Carbon\Carbon::parse($this->tanggal_sampai);

        if ($start->diffInDays($end) > 365) {
            $this->addError('tanggal_sampai', 'Rentang tanggal tidak boleh lebih dari 1 tahun.');
            return;
        }

        // Get nasabah with lembaga check if not admin
        $query = ModelsNasabah::where('rekening', $this->rekening);
        if (!auth()->user()->hasRole('admin')) {
            $query->where('saldo_id', $this->lembaga_id);
        }

        $this->nasabah = $query->first();
        if (!$this->nasabah) {
            $this->addError('rekening', 'Nomor rekening tidak ditemukan');
            return;
        }

        $query = Nasabah_transaksi::where('nasabah_id', $this->nasabah->id)
            ->with('user')
            ->whereBetween('created_at', [
                $this->tanggal_dari . ' 00:00:00',
                $this->tanggal_sampai . ' 23:59:59'
            ]);

        $this->totalSetor = $query->clone()->sum('debit');
        $this->totalTarik = $query->clone()->sum('credit');
        $this->transaksi = $query->orderBy('created_at')->get();
    }

    public function export()
    {
        if (empty($this->transaksi)) {
            return;
        }

        $data = [
            'transaksi' => $this->transaksi,
            'tanggal_dari' => $this->tanggal_dari,
            'tanggal_sampai' => $this->tanggal_sampai,
            'nasabah' => $this->nasabah
        ];

        return Excel::download(new LaporanNasabah($data), 'Laporan Mutasi Nasabah ' . $this->nasabah->nama . ' ' . date('Y-m-d H:i') . '.xlsx');
    }

    public function render()
    {
        return view('livewire.laporan.nasabah');
    }
}
