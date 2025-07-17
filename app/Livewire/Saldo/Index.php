<?php

namespace App\Livewire\Saldo;

use App\Models\Saldo;
use Mary\Traits\Toast;
use App\Models\Setting;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\WhatsappPesan;
use Illuminate\Support\Facades\DB;

class Index extends Component
{

    use WithPagination;
    use Toast;

    public $modal = false;
    public $saldoId;
    public $perPage = 5;
    public $nama;

    public function openModal($id)
    {
        $this->nama = Saldo::find($id)?->nama;
        $this->saldoId = $id;
        $this->modal = true;
    }

    public function closeModal()
    {
        $this->modal = false;
        $this->reset('nama', 'saldoId');
    }

    public function save()
    {

        $this->validate([
            'nama' => 'required|unique:saldo,nama,' . $this->saldoId,
        ]);



        try {
            DB::beginTransaction();

            $saldo = Saldo::find($this->saldoId);

            if (!$saldo) {
                $saldo = new Saldo();
            }
            $saldo->nama = $this->nama;
            $saldo->save();

            // Gunakan saldo yang sudah disimpan
            $defaultSettings = Setting::whereNull('saldo_id')->get();

            foreach ($defaultSettings as $default) {
                Setting::create([
                    'saldo_id' => $saldo->id, // gunakan $saldo, bukan $s
                    'nama' => $default->nama,
                    'isi' => $default->isi,
                ]);
            }

            $defaultPesan = WhatsappPesan::whereNull('saldo_id')->get();

            foreach ($defaultPesan as $pesan) {
                WhatsappPesan::create([
                    'saldo_id' => $saldo->id, // gunakan $saldo, bukan $s
                    'pesan' => $pesan->pesan,
                    'jenis' => $pesan->jenis,
                    'status' => $pesan->status,
                ]);
            }

            DB::commit();

            $this->saldoId = null;

            $this->success(
                'Berhasil',
                timeout: 5000,
                position: 'toast-top toast-end'
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error(
                'Gagal Menambahkan Lembaga',
                timeout: 5000,
                position: 'toast-top toast-end'
            );
        }



        $this->modal = false;
        $this->reset('nama', 'saldoId');
    }

    public function render()
    {
        $saldo = Saldo::Paginate($this->perPage);

        $headers = [
            ['key' => 'nama', 'label' => 'Nama'],
        ];


        return view('livewire.saldo.index', compact('saldo', 'headers'));
    }
}
