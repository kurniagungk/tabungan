<?php

namespace App\Http\Livewire\Nasabah;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;

use App\wilayah;
use App\Nasabah;

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
    public $dataProvinsi;
    public $dataKabupaten;
    public $dataKecamatan;
    public $dataDesa;
    public $provinsi;
    public $kabupaten;
    public $kecamatan;
    public $desa;
    public $pasword;

    public function mount()
    {
        $this->dataProvinsi = Wilayah::whereRaw('CHAR_LENGTH(kode) = 2')
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
            'provinsi.required' => 'PROVINSI TIDAK BOLEH KOSONG',
            'kabupaten.required' => 'KABUPATEN TIDAK BOLEH KOSONG',
            'kecamatan.required' => 'KECAMATAN TIDAK BOLEH KOSONG',
            'desa.required' => 'DESA TIDAK BOLEH KOSONG',
            'pasword.required' => 'PASWORD TIDAK BOLEH KOSONG',

        ];

        $validatedData = $this->validate([
            'nis' => 'required|unique:santri|max:255',
            'nama' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'date',
            'alamat' => 'required',
            'telepon' => 'required',
            'jenis_kelamin' => 'required',
            'nama_wali' => 'required',
            'photo' => 'image|max:2048',
            'photo' => 'image|max:2048',
            'provinsi' => 'required',
            'kabupaten' => 'required',
            'kecamatan' => 'required',
            'desa' => 'required',
            'pasword' => 'required'

        ], $messages);

        $photo = $this->photo->store('photos', 'public');


        $data = array(
            'id' => Str::uuid(),
            'nis' => $this->nis,
            'nama' => $this->nama,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'alamat' => $this->alamat,
            'telepon' => $this->telepon,
            'jenis_kelamin' => $this->jenis_kelamin,
            'wali' => $this->nama_wali,
            'foto' => $photo,
            'provinsi_id' => $this->provinsi,
            'kabupaten_id' => $this->kabupaten,
            'kecamatan_id' => $this->kecamatan,
            'desa_id' => $this->desa,
            'pasword' => $this->pasword,
            'status' => 1,
        );


        Nasabah::create($data);

        session()->flash('success', 'Data Santri successfully add.');

        return redirect()->route('nasabah.index');
    }

    public function render()
    {
        return view('livewire.nasabah.create');
    }
}
