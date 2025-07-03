<?php

namespace App\Livewire\Saldo;

use App\Models\Saldo;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    use WithPagination;

    public $modal = false;
    public $saldoId;
    public $perPage = 5;
    public $nama;

    public function openModal($id)
    {
        $this->nama = Saldo::find($id)?->nama;
        $this->modal = true;
    }

    public function closeModal()
    {
        $this->modal = false;
        $this->reset('nama', 'saldoId');
    }

    public function save()
    {
        $saldo = Saldo::find($this->saldoId);
        if (!$saldo) {
            $saldo = new Saldo();
        }
        $saldo->nama = $this->nama;
        $saldo->save();
        $this->modal = false;
        $this->reset('nama', 'saldoId');
    }

    public function render()
    {
        $saldo = Saldo::Paginate($this->perPage);

        $headers = [
            ['key' => 'nama', 'label' => 'Nama'],
            ['key' => 'saldo', 'label' => 'Saldo'],
        ];


        return view('livewire.saldo.index', compact('saldo', 'headers'));
    }
}
