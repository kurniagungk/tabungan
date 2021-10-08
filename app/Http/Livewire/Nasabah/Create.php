<?php

namespace App\Http\Livewire\Nasabah;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

use App\wilayah;
use App\Nasabah;
use App\Saldo;
use App\Transaksi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Create extends Component
{

    use WithFileUploads;

    public $nis;
    public $nama;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $alamat;
    public $telepon;
    public $jenis_kelamin;
    public $nama_wali;
    public $photo;
    public $saldo;
    public $photoStatus = false;
    public $pasword = 1234;
    public $card;
    public $wa = false;


    public function updatedphoto()
    {
        $this->validate([
            'photo' => 'image|max:5000|mimes:png,jpeg,bmp,gif', // 1MB Max
        ]);
    }


    public function store()
    {

        $messages = [
            'nis.required'    => 'NIS TIDAK BOLEH KOSONG',
            'nis.unique'    => 'NIS TIDAK BOLEH SAMA',
            'nama.required'    => 'NAMA TIDAK BOLEH KOSONG',
            'tanggal_lahir.required'    => 'TANGGAL LAHIR TIDAK BOLEH KOSONG',
            'alamat.required'    => 'ALAMAT TIDAK BOLEH KOSONG',
            'jenis_kelamin.required'    => 'JENIS KELAMIN TIDAK BOLEH KOSONG',
            'nama_wali.required'    => 'NAMA WALI TIDAK BOLEH KOSONG',
            'tempat_lahir.required'    => 'TEMPAT LAHIR TIDAK BOLEH KOSONG',
            'telepon.required'    => 'NO HP TIDAK BOLEH KOSONG',
            'pasword.required' => 'PASWORD TIDAK BOLEH KOSONG',

        ];

        $validatedData = $this->validate([
            'nama' => 'required',
            'jenis_kelamin' => 'required',
            'pasword' => 'required|min:4',
            'photo' => 'nullable|image|max:5000|mimes:png,jpeg,bmp,gif',

        ], $messages);

        if ($this->photo)
            $photo = $this->photo->store('photos', 'public');
        else
            $photo = 'photos/user.png';




        $data = array(
            'id' => Str::uuid(),
            'nama' => $this->nama,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'alamat' => $this->alamat,
            'telepon' => $this->telepon,
            'jenis_kelamin' => $this->jenis_kelamin,
            'wali' => $this->nama_wali,
            'foto' => $photo,
            'password' => $this->pasword,
            'card' => $this->card,
            'saldo' => 0,
            'wa' => $this->wa,
        );


        $nasabah = Nasabah::create($data);

        if ($this->saldo > 0)
            try {

                DB::beginTransaction();

                $nasabah->saldo += $this->saldo;

                $nasabah->save();

                $saldo =  $nasabah->transaksi()->create([
                    'user_id' => Auth::id(),
                    'debit' => $this->saldo,
                    'ref' => 'tabungan',
                    'keterangan' => 'Setor Awal'
                ]);

                $saldo = Saldo::where('nama', 'tabungan')->first();
                $saldo->saldo += $this->saldo;
                $saldo->save();


                Transaksi::create([
                    'debit' => $this->saldo,
                    'keterangan' => 'tabungan',
                    'ref' => $saldo->id
                ]);


                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                dd($e);
                return session()->flash('danger', 'Gagal saldo Tunai');
            }

        return redirect()->route('nasabah.show', $nasabah->id);
    }

    public function updatedfoto()
    {
        $this->validate([
            'foto' => 'image|max:5000|mimes:png,jpeg,bmp,gif', // 1MB Max
        ]);
    }

    public function render()
    {
        return view('livewire.nasabah.create');
    }
}
