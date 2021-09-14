<?php

namespace App\Http\Livewire\Setting;

use App\Setting;
use Livewire\Component;

class Tabungan extends Component
{

    public $tanggal, $biaya, $minimal;

    public function mount()
    {
        $setting = Setting::whereIn('nama', ['biaya_tanggal', 'biaya_admin', 'saldo_minimal'])->get();

        $this->tanggal = $setting[0]->isi;
        $this->minimal = $setting[1]->isi;
        $this->biaya = $setting[2]->isi;
    }

    public function store()
    {
        $validatedData = $this->validate([
            'tanggal' => 'required|numeric|min:1|max:29',
            'biaya' => 'required|min:1',
            'minimal' => 'required|min:1',
        ]);

        Setting::where("nama", "biaya_tanggal")->update(["isi" => $this->tanggal]);
        Setting::where("nama", "biaya_admin")->update(["isi" => $this->biaya]);
        Setting::where("nama", "saldo_minimal")->update(["isi" => $this->minimal]);

        session()->flash('pesan', 'Setting berhasil disimpan');
    }

    public function render()
    {
        return view('livewire.setting.tabungan');
    }
}