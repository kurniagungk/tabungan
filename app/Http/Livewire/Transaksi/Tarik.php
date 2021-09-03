<?php

namespace App\Http\Livewire\Transaksi;

use App\Nasabah;
use App\Saldo;
use App\Setting;
use App\Transaksi;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Tarik extends Component
{


    public $rekening;
    public $nasabah;
    public $nasabah_id;
    public $tarik;
    public $tanggal;
    public $modal = false;


    protected $listeners = ['password' => 'show', 'close' => 'close'];


    protected $rules = [
        'tarik' => 'required|min:1|numeric',
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
        $saldoMinimal = Setting::where('nama', 'saldo_minimal')->first();



        if ($nasabah->saldo - $this->tarik <  $saldoMinimal->isi || $nasabah->saldo < $saldoMinimal->isi)
            return  $this->addError('tarik', 'Saldo Kurang');

        try {

            DB::beginTransaction();

            $nasabah->saldo -= $this->tarik;

            $nasabah->save();

            $tarik =  $nasabah->transaksi()->create([
                'user_id' => Auth::id(),
                'created_at' => $this->tanggal,
                'credit' => $this->tarik
            ]);

            $saldo = Saldo::where('nama', 'tabungan')->first();
            $saldo->saldo -= $this->tarik;
            $saldo->save();


            Transaksi::create([
                'credit' => $this->tarik,
                'keterangan' => 'Tabungan',
                'ref' => $tarik->id
            ]);


            DB::commit();

            $this->emit('start');

            $this->reset('nasabah', 'setor', 'rekening');
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return session()->flash('danger', 'Gagal tarik Tunai');;
        }
    }






    public function render()
    {

        return view('livewire.transaksi.tarik');
    }
}
