<?php

namespace App\Http\Livewire\Transaksimitra;


use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use App\Nasabah;
use App\Transaksi;
use App\User;

class Create extends Component
{
    public $nis;
    public $jumlah;
    public $nasabah;
    public $password;



    public function updatingNis($value)
    {
        $nasabah = Nasabah::where('nis', $value)->first();



        if ($nasabah) {
            $this->nasabah = $nasabah;
            $this->resetErrorBag();
            $this->emit('nasabah');
        } else {
            $this->addError('nis', 'Nasabah tidak ditemukan');
            $this->reset('nasabah');
        }
    }

    public function bayar()
    {
        $nasabah = $this->nasabah;

        if (is_null($nasabah))
            $this->reset('nis');


        $this->validate([
            'nis' => 'required|',
            'jumlah' => 'required|integer',
            'password' => 'required|integer',
        ]);

        if ($this->password == $nasabah->password) {

            $sisa = $nasabah->saldo - $this->jumlah;

            if ($sisa < 0) {
                return  session()->flash('danger', 'Saldo tidak mencukupi');
                $this->emit('saldo');
            }


            $nasabah->update(['saldo' => $sisa]);

            $bank = User::find(1);

            $bank->saldo -= $this->jumlah;

            $bank->save();


            $mitra = User::find(Auth::id());

            $mitra->saldo += $this->jumlah;

            $mitra->save();

            Transaksi::create([
                'id' => Str::uuid(),
                'santri_id' => $nasabah->id,
                'mitra_id' => '2',
                'jumlah' => $this->jumlah,
                'jenis' => 'tarik'
            ]);
            session()->flash('message', 'Pembayaran berhasil, sisa saldo Rp. ' . $nasabah->saldo);
            $this->reset();
            $this->emit('berhasil');
        } else {
            $this->reset('password');
            $this->addError('password', 'password salah');
            $this->emit('nasabah');
        }
    }


    public function render()
    {
        return view('livewire.transaksimitra.create');
    }
}
