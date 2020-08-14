<?php

namespace App\Http\Livewire\Mitra;

use Livewire\Component;
use Illuminate\Support\Str;

use App\Mitra;
use App\Jurnal;

class Tariksaldo extends Component
{
    public $mitraId;
    public $jumlah;
    public $keterangan;

    public function tarik()
    {
        $this->validate([
            'mitraId' => 'required',
            'jumlah' => 'required|integer',
            'keterangan' => 'required|max:255',
        ]);

        Jurnal::create([
            'id' => Str::uuid(),
            'mitra_id' => $this->mitraId,
            'jumlah' => $this->jumlah,
            'keterangan' => $this->keterangan
        ]);
        session()->flash('pesan', 'berhasil melakukan penarikan saldo');
        $this->reset();
    }

    public function render()
    {
        $mitra = Mitra::get();

        $jurnal = Jurnal::with("mitra")
            ->whereDate('created_at', '=', date("Y-m-d"))->get();

        return view('livewire.mitra.tariksaldo', compact("mitra"), compact("jurnal"));
    }
}
