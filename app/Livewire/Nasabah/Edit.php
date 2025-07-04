<?php

namespace App\Livewire\Nasabah;

use Mary\Traits\Toast;
use App\Models\Nasabah;
use Livewire\Component;

use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;


class Edit extends Component
{

    use WithFileUploads;
    use Toast;

    public $nasabah_id;
    public $nama;
    public $nisn;
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
    public $tahun;
    public $saldo;
    public $wa;

    public function mount(Nasabah $nasabah)
    {
        $this->nama = $nasabah->nama;
        $this->nisn = $nasabah->nisn;
        $this->tempat_lahir = $nasabah->tempat_lahir;
        $this->tanggal_lahir = $nasabah->tanggal_lahir;
        $this->alamat = $nasabah->alamat;
        $this->telepon = $nasabah->telepon;
        $this->jenis_kelamin = $nasabah->jenis_kelamin;
        $this->wali = $nasabah->wali;
        $this->photo_old = $nasabah->foto;
        $this->nasabah_id = $nasabah->id;
        $this->pasword =  $nasabah->password;
        $this->saldo =  $nasabah->saldo;
        $this->wa =  $nasabah->wa;
        $this->tahun = $nasabah->tahun . '-01';
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
            'jenis_kelamin' => 'required',
            'pasword' => 'required|min:4|max:6'

        ], $messages);





        $nasabah = Nasabah::find($this->nasabah_id);

        $nasabah->nama = $this->nama;
        $nasabah->nisn = $this->nisn;
        $nasabah->tempat_lahir = $this->tempat_lahir;
        $nasabah->tanggal_lahir = $this->tanggal_lahir;
        $nasabah->alamat = $this->alamat;
        $nasabah->telepon = $this->telepon;
        $nasabah->jenis_kelamin = $this->jenis_kelamin;
        $nasabah->wali = $this->wali;
        $nasabah->tahun = $this->tahun;
        $nasabah->password = $this->pasword;
        $nasabah->wa = $this->wa;

        if ($this->photo) {
            if ($nasabah->foto != "foto/user.png")
                Storage::disk('public')->delete($nasabah->foto);
            $photo = $this->photo->store('photos', 'public');
            $nasabah->foto = $photo;
        }


        $nasabah->save();

        $this->toast(
            type: 'success',
            title: 'Berhasil Mengubah Data Nasabah',
            description: null,                  // optional (text)
            position: 'toast-top toast-end',    // optional (daisyUI classes)
            icon: 'o-information-circle',       // Optional (any icon)
            css: 'alert-info',                  // Optional (daisyUI classes)
            timeout: 3000,                      // optional (ms)
            redirectTo: Route('nasabah.index')                    // optional (uri)
        );
    }

    public function render()
    {

        $jenis_kelamins = [
            ['id' => null, 'name' => '',],
            ['id' => 'Laki-Laki', 'name' => 'Laki-laki',],
            ['id' => 'Perempuan', 'name' => 'Perempuan',] // <-- this
        ];

        $waSelect = [
            ['id' => '1', 'name' => 'Ya',],
            ['id' => '0', 'name' => 'Tidak',]
        ];


        return view('livewire.nasabah.edit', compact('jenis_kelamins', 'waSelect'));
    }
}
