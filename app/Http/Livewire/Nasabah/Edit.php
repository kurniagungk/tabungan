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
    public $nis;
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
    public $NewPhoto;
    public $dataProvinsi;
    public $dataKabupaten;
    public $dataKecamatan;
    public $dataDesa;
    public $provinsi;
    public $kabupaten;
    public $kecamatan;
    public $desa;
    public $pasword;

    public function mount(Nasabah $nasabah)
    {

        $this->nis = $nasabah->nis;
        $this->nama = $nasabah->nama;
        $this->tempat_lahir = $nasabah->tempat_lahir;
        $this->tanggal_lahir = $nasabah->tanggal_lahir;
        $this->alamat = $nasabah->alamat;
        $this->telepon = $nasabah->telepon;
        $this->jenis_kelamin = $nasabah->jenis_kelamin;
        $this->wali = $nasabah->wali;
        $this->photo = $nasabah->foto;
        $this->nasabah_id = $nasabah->id;
        $this->provinsi = $nasabah->provinsi_id;
        $this->kabupaten = $nasabah->kabupaten_id;
        $this->kecamatan =  $nasabah->kecamatan_id;
        $this->desa =  $nasabah->desa_id;
        $this->pasword =  $nasabah->pasword;
        $this->data();
    }

    public function data()
    {
        $this->dataProvinsi = Wilayah::whereRaw('CHAR_LENGTH(kode) = 2')
            ->get();
        $this->dataKabupaten = Wilayah::whereRaw('LEFT(kode, 2) = "' . $this->provinsi . '"')
            ->whereRaw('CHAR_LENGTH(kode) = 5')
            ->get();
        $this->dataKecamatan = Wilayah::whereRaw('LEFT(kode, 5) = "' . $this->kabupaten . '"')
            ->whereRaw('CHAR_LENGTH(kode) = 8')
            ->get();
        $this->dataDesa = Wilayah::whereRaw('LEFT(kode, 8) = "' . $this->kecamatan . '"')
            ->whereRaw('CHAR_LENGTH(kode) = 13')
            ->get();
    }

    public function updatingprovinsi($value)
    {
        $this->reset('kabupaten', 'kecamatan', 'desa');
        $this->dataKabupaten = Wilayah::whereRaw('LEFT(kode, 2) = "' . $value . '"')
            ->whereRaw('CHAR_LENGTH(kode) = 5')
            ->get();
    }

    public function updatingkabupaten($value)
    {
        $this->reset('kecamatan', 'desa');
        $this->dataKecamatan = Wilayah::whereRaw('LEFT(kode, 5) = "' . $value . '"')
            ->whereRaw('CHAR_LENGTH(kode) = 8')
            ->get();
    }

    public function updatingkecamatan($value)
    {
        $this->reset('desa');
        $this->dataDesa = Wilayah::whereRaw('LEFT(kode, 8) = "' . $value . '"')
            ->whereRaw('CHAR_LENGTH(kode) = 13')
            ->get();
    }



    public function update()
    {



        $messages = [
            'nis.required'    => 'NIS TIDAK BOLEH KOSONG',
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
            'nis' => ['required', Rule::unique('nasabah')->ignore($this->nis, 'nis')],
            'nama' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'date',
            'alamat' => 'required',
            'telepon' => 'required',
            'jenis_kelamin' => 'required',
            'wali' => 'required',
            'provinsi' => 'required',
            'kabupaten' => 'required',
            'kecamatan' => 'required',
            'desa' => 'required',
            'pasword' => 'required'

        ], $messages);





        $nasabah = Nasabah::find($this->nasabah_id);


        if ($this->NewPhoto) {

            $validatedData = $this->validate([
                'NewPhoto' => 'image|max:2048',
            ]);

            Storage::disk('public')->delete($this->photo);

            $photo = $this->NewPhoto->store('photos', 'public');

            $nasabah->foto = $photo;
        }
        $nasabah->nis = $this->nis;
        $nasabah->nama = $this->nama;
        $nasabah->tempat_lahir = $this->tempat_lahir;
        $nasabah->tanggal_lahir = $this->tanggal_lahir;
        $nasabah->alamat = $this->alamat;
        $nasabah->telepon = $this->telepon;
        $nasabah->jenis_kelamin = $this->jenis_kelamin;
        $nasabah->wali = $this->wali;
        $nasabah->provinsi_id = $this->provinsi;
        $nasabah->kabupaten_id = $this->kabupaten;
        $nasabah->kecamatan_id = $this->kecamatan;
        $nasabah->desa_id = $this->desa;
        $nasabah->pasword = $this->pasword;


        $nasabah->save();


        session()->flash('pesan', 'Data Nasabah successfully edite.');
        return redirect()->route('nasabah.index');
    }

    public function render()
    {
        return view('livewire.nasabah.edit');
    }
}
