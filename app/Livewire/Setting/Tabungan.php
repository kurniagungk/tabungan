<?php

namespace App\Livewire\Setting;

use App\Models\Saldo;
use Mary\Traits\Toast;
use App\Models\Setting;
use Livewire\Component;


class Tabungan extends Component
{

    use Toast;

    public $tanggal, $biaya, $minimal, $habis, $saldo_id, $hook;

    public function mount()
    {

        $user = auth()->user();
        $admin = $user->hasRole('admin');

        $saldo_id = null;

        if (!$admin) {
            $saldo_id = $user->saldo_id;
        }

        $this->saldo_id = $saldo_id;

        $this->dataSetting();
    }

    public function dataSetting()
    {


        $saldo_id = $this->saldo_id;

        $setting = Setting::whereIn('nama', ['biaya_tanggal', 'biaya_admin', 'saldo_minimal', 'saldo_habis', 'whatsapp_webhook'])->where('saldo_id', $saldo_id)->get();


        $this->tanggal = $setting[0]->isi;
        $this->biaya = $setting[1]->isi;
        $this->minimal = $setting[2]->isi;
        $this->habis = $setting[3]->isi;
        $this->hook = $setting[4]->isi;
    }

    public function updatedSaldoId()
    {
        $this->dataSetting();
    }

    public function store()
    {
        $validatedData = $this->validate([
            'tanggal' => 'required|numeric|min:1|max:29',
            'biaya' => 'required|min:1',
            'minimal' => 'required|min:1',
        ]);

        $saldo_id = $this->saldo_id;

        Setting::where('saldo_id', $saldo_id)->where("nama", "biaya_tanggal")->update(["isi" => $this->tanggal]);
        Setting::where('saldo_id', $saldo_id)->where("nama", "biaya_admin")->update(["isi" => $this->biaya]);
        Setting::where('saldo_id', $saldo_id)->where("nama", "saldo_minimal")->update(["isi" => $this->minimal]);
        Setting::where('saldo_id', $saldo_id)->where("nama", "saldo_habis")->update(["isi" => $this->habis]);
        Setting::where('saldo_id', $saldo_id)->where("nama", "whatsapp_webhook")->update(["isi" => $this->hook]);

        $this->success(
            'Setting berhasil disimpan',
            timeout: 5000,
            position: 'toast-top toast-end'
        );
    }

    public function render()
    {

        $dataSaldo = Saldo::all()->prepend((object)[
            'id' => null,
            'nama' => 'deafult Setting',
        ]);


        return view('livewire.setting.tabungan', compact('dataSaldo'));
    }
}
