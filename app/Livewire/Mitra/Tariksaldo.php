<?php

namespace App\Livewire\Mitra;

use Livewire\Component;
use Illuminate\Support\Str;

use App\Mitra;
use App\Jurnal;
use App\Models\User;

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

        $mitra = User::find($this->mitraId);



        if ($mitra->saldo - $this->jumlah < 0) {
            return session()->flash('danger', 'saldo tidak mencukupi');
        }



        Jurnal::create([
            'id' => Str::uuid(),
            'mitra_id' => $this->mitraId,
            'jumlah' => $this->jumlah,
            'keterangan' => $this->keterangan
        ]);




        $mitra->saldo -= $this->jumlah;
        $mitra->save();

        session()->flash('pesan', 'berhasil melakukan penarikan saldo');
        $this->reset();
    }

    public function render()
    {
        $mitra = User::where('id', '!=', 1)
            ->get();
        $jurnal = Jurnal::with("mitra")
            ->whereDate('created_at', '=', date("Y-m-d"))->get();

        return view('livewire.mitra.tariksaldo', compact("mitra"), compact("jurnal"));
    }
}
