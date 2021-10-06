<?php

namespace App\Http\Livewire\Nasabah;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

use App\wilayah;
use App\Nasabah;

class Edit extends Component
{

    use WithFileUploads;

    public $nasabah_id;
    public $nama;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $alamat;
    public $sekolah;
    public $asrama;
    public $telepon;
    public $jenis_kelamin;
    public $wali;
    public $photo;
    public $photo_old;
    public $pasword;
    public $wa;

    public function mount(Nasabah $nasabah)
    {
        $this->nama = $nasabah->nama;
        $this->tempat_lahir = $nasabah->tempat_lahir;
        $this->tanggal_lahir = $nasabah->tanggal_lahir;
        $this->alamat = $nasabah->alamat;
        $this->telepon = $nasabah->telepon;
        $this->jenis_kelamin = $nasabah->jenis_kelamin;
        $this->wali = $nasabah->wali;
        $this->photo_old = $nasabah->foto;
        $this->nasabah_id = $nasabah->id;
        $this->pasword =  $nasabah->password;
        $this->wa =  $nasabah->wa;
    }


    public function update()
    {



        $messages = [
            'nis.unique'    => 'NIS TIDAK BOLEH SAMA',
            'nama.required'    => 'NAMA TIDAK BOLEH KOSONG',
            'tanggal_lahir.required'    => 'TANGGAL LAHIR TIDAK BOLEH KOSONG',
            'alamat.required'    => 'ALAMAT TIDAK BOLEH KOSONG',
            'jenis_kelamin.required'    => 'JENIS KELAMIN TIDAK BOLEH KOSONG',
            'wali.required'    => 'NAMA WALI TIDAK BOLEH KOSONG',
            'tempat_lahir.required'    => 'TEMPAT LAHIR TIDAK BOLEH KOSONG',
            'telepon.required'    => 'NO HP TIDAK BOLEH KOSONG',
            'provinsi.required' => 'PROVINSI TIDAK BOLEH KOSONG',
            'kabupaten.required' => 'KABUPATEN TIDAK BOLEH KOSONG',
            'kecamatan.required' => 'KECAMATAN TIDAK BOLEH KOSONG',
            'desa.required' => 'DESA TIDAK BOLEH KOSONG',
            'pasword.required' => 'PASWORD TIDAK BOLEH KOSONG',

        ];

        $validatedData = $this->validate([
            'nama' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'date',
            'alamat' => 'required',
            'telepon' => 'required',
            'jenis_kelamin' => 'required',
            'pasword' => 'required|min:4|max:6'

        ], $messages);





        $nasabah = Nasabah::find($this->nasabah_id);

        $nasabah->nama = $this->nama;
        $nasabah->tempat_lahir = $this->tempat_lahir;
        $nasabah->tanggal_lahir = $this->tanggal_lahir;
        $nasabah->alamat = $this->alamat;
        $nasabah->telepon = $this->telepon;
        $nasabah->jenis_kelamin = $this->jenis_kelamin;
        $nasabah->wali = $this->wali;
        $nasabah->password = $this->pasword;
        $nasabah->wa = $this->wa;

        if ($this->photo) {
            if ($nasabah->foto != "foto/user.png")
                Storage::disk('public')->delete($nasabah->foto);
            $photo = $this->photo->store('photos', 'public');
            $nasabah->foto = $photo;
        }


        $nasabah->save();


        session()->flash('pesan', 'Data Nasabah successfully edite.');
        return redirect()->route('nasabah.index');
    }

    public function render()
    {
        return view('livewire.nasabah.edit');
    }
}
