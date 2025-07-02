<?php

namespace App\Livewire\Setting;

use App\Models\Setting;
use Livewire\Component;


class Tabungan extends Component
{

    public $tanggal, $biaya, $minimal, $habis;

    public function mount()
    {
        $setting = Setting::whereIn('nama', ['biaya_tanggal', 'biaya_admin', 'saldo_minimal', 'saldo_habis'])->get();


        $this->tanggal = $setting[0]->isi;
        $this->biaya = $setting[1]->isi;
        $this->minimal = $setting[2]->isi;
        $this->habis = $setting[3]->isi;
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
        Setting::where("nama", "saldo_habis")->update(["isi" => $this->habis]);

        session()->flash('pesan', 'Setting berhasil disimpan');
    }

    public function render()
    {
        return view('livewire.setting.tabungan');
    }
}
