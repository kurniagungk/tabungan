<?php

namespace App\Http\Livewire\Transaksi;

use App\Nasabah;
use App\Saldo;
use App\Setting;
use App\Transaksi;
use App\Whatapps;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class Tarik extends Component
{


    public $rekening;
    public $nasabah;
    public $nasabah_id;
    public $tarik;
    public $sisa;
    public $tanggal;
    public $keterangan;
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
        $this->reset('rekening',  'nasabah');
    }

    public function find()
    {

        $this->reset('nasabah');
        $rekening =  substr($this->rekening, 0, 3) == 'NSB' ||  substr($this->rekening, 0, 3) == 'nsb'  ? $this->rekening : 'NSB' . $this->rekening;
        $nasabah =  Nasabah::where('rekening', $rekening)->first();

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
        $rekening =  substr($this->rekening, 0, 3) == 'NSB' ||  substr($this->rekening, 0, 3) == 'nsb'  ? $this->rekening : 'NSB' . $this->rekening;
        $nasabah =  Nasabah::where('rekening', $rekening)->first();
        $this->nasabah = $nasabah;
        $this->sisa = $nasabah->saldo;
        $this->nasabah_id = $nasabah->id;
    }

    public function updatedTarik($value)
    {
        if ($value)
            $this->sisa = $this->nasabah->saldo - $this->tarik;
        else
            $this->sisa = $this->nasabah->saldo;
    }

    public function removeModal()
    {
        $this->reset('modal');
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
                'credit' => $this->tarik,
                'ref' => 'tabungan',
                'keterangan' => $this->keterangan
            ]);

            $saldo = Saldo::where('nama', 'tabungan')->first();
            $saldo->saldo -= $this->tarik;
            $saldo->save();


            Transaksi::create([
                'credit' => $this->tarik,
                'keterangan' => 'tabungan',
                'ref' => $tarik->id
            ]);


            DB::commit();


            if ($nasabah->saldo <= $saldoMinimal->isi)
                $this->whatapps($nasabah);


            $this->emit('start');

            $this->reset('nasabah', 'tarik', 'sisa', 'keterangan', 'rekening');
        } catch (\Exception $e) {
            DB::rollBack();
            return session()->flash('danger', 'Gagal tarik Tunai');;
        }
    }


    public function whatapps($nasabah)
    {



        $setting = Setting::where('nama', 'saldo_habis')->first();

        $replace = ['{nama}', '{saldo}'];
        $variable = [$nasabah->nama, number_format($nasabah->saldo, 2, ',', '.')];
        $pesan = str_replace($replace, $variable, $setting->isi);

        try {
            $response = Http::post('localhost:3000/send', [

                'token' => 'VGFidW5nYW4gQWxrYWhmaSBTb21hbGFuZ3U=',
                'no' => $nasabah->telepon,
                'pesan' => $pesan

            ]);



            $status = $response->ok() ? 'berhasil' : 'gagal';

            Whatapps::create([
                'nomer' => $nasabah->telepon,
                'nama' => $nasabah->nama,
                'status' => $status,
                'jenis' => 'kirim'
            ]);
        } catch (\Exception $e) {
            Whatapps::create([
                'nomer' => $nasabah->telepon,
                'nama' => $nasabah->nama,
                'status' => 'gagal',
                'jenis' => 'kirim'
            ]);
        }
    }



    public function render()
    {

        return view('livewire.transaksi.tarik');
    }
}
