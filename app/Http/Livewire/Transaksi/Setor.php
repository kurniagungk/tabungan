<?php

namespace App\Http\Livewire\Transaksi;

use App\Nasabah;
use App\Saldo;
use App\Transaksi;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Setor extends Component
{


    public $rekening;
    public $nasabah;
    public $setor;
    public $tanggal;
    public $modal = false;
    public $nasabah_id;


    protected $listeners = ['password' => 'show', 'close' => 'close'];

    protected $rules = [
        'setor' => 'required|min:1|numeric',
        'tanggal' => 'required|date',
    ];

    public function mount()
    {
        $this->tanggal = date("Y-m-d\TH:i:s");
    }


    public function close()
    {
        $this->dispatchBrowserEvent('close');
        $this->reset('rekening', 'modal', 'nasabah');
    }

    public function find()
    {
        $this->reset('nasabah');
        $nasabah =  Nasabah::where('rekening', $this->rekening)->first();

        if (!$nasabah) {
            $this->reset('nasabah');
            return $this->addError('nis', 'Nis tidak ditemukan');
        }


        $this->modal = true;
        $this->nasabah_id = $nasabah->id;
        $this->dispatchBrowserEvent('modal');
    }


    public function show()
    {
        $this->dispatchBrowserEvent('show');
        $this->reset('modal');
        $nasabah =  Nasabah::where('rekening', $this->rekening)->first();
        $this->nasabah = $nasabah;
        $this->nasabah_id = $nasabah->id;
    }

    public function save()
    {


        $this->validate();


        $nasabah = $this->nasabah;

        try {

            DB::beginTransaction();

            $nasabah->saldo += $this->setor;

            $nasabah->save();

            $setor =  $nasabah->transaksi()->create([
                'user_id' => Auth::id(),
                'created_at' => $this->tanggal,
                'debit' => $this->setor
            ]);

            $saldo = Saldo::where('nama', 'tabungan')->first();
            $saldo->saldo += $this->setor;
            $saldo->save();


            Transaksi::create([
                'debit' => $this->setor,
                'keterangan' => 'Tabungan',
                'ref' => $setor->id
            ]);


            DB::commit();

            $this->emit('start');

            $this->reset('nasabah', 'setor', 'rekening');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return session()->flash('danger', 'Gagal Setor Tunai');;
        }
    }




    public function render()
    {
        return view('livewire.transaksi.setor');
    }
}
