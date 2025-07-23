<?php

namespace App\Livewire\Nasabah;

use App\Models\Saldo;

use Mary\Traits\Toast;

use App\Models\Nasabah;
use Livewire\Component;
use App\Models\Whatsapp;
use App\Models\Transaksi;
use Illuminate\Support\Str;
use App\Models\WhatsappPesan;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use App\Jobs\KirimPesanWhatsappJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;


class Create extends Component
{

    use WithFileUploads;
    use Toast;

    public $nisn;
    public $nama;
    public $tempat_lahir;
    public $tanggal_lahir;
    public $alamat;
    public $telepon;
    public $jenis_kelamin;
    public $nama_wali;
    public $photo;
    public $saldo;
    public $saldo_id;
    public $tahun;
    public $photoStatus = false;
    public $pasword = 1234;
    public $card;
    public $photoUrl;
    public $wa = 0;


    public function updatedphoto()
    {
        $this->validate([
            'photo' => 'image|max:5000|mimes:png,jpeg,bmp,gif', // 1MB Max
        ]);

        $this->photoUrl = null;
        $this->tahun = date('Y');
    }


    public function store()
    {

        $user = auth()->user();
        $admin = $user->hasRole('admin');
        if (!$admin) {
            $this->saldo_id = $user->saldo_id;
        }

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
            'tahun' => 'TAHUN TIDAK BOLEH KOSONG',
        ];

        $validatedData = $this->validate([
            'nama' => 'required',
            'tahun' => 'required',
            'saldo_id' => 'required',
            'jenis_kelamin' => 'required',
            'pasword' => 'required|min:4',
            'jenis_kelamin' => 'required',
            'photo' => 'nullable|image|max:5000|mimes:png,jpeg,bmp,gif',

        ], $messages);




        if ($this->photoUrl) {
            $photo = $this->photoUrl;
        } else {

            if ($this->photo)
                $photo = $this->photo->store('photos', 'public');
            else
                $photo = 'photos/user.png';
        }


        try {



            DB::beginTransaction();

            $data = array(
                'id' => Str::uuid(),
                'nisn' => $this->nisn,
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
                'tahun' => $this->tahun,
                'saldo' => 0,
                'saldo_id' => $this->saldo_id,
                'wa' => $this->wa,
            );


            $nasabah = Nasabah::create($data);

            if ($this->saldo > 0) {



                $nasabah->saldo += $this->saldo;

                $nasabah->save();

                $setor =  $nasabah->transaksi()->create([
                    'user_id' => Auth::id(),
                    'debit' => $this->saldo,
                    'ref' => 'tabungan',
                    'keterangan' => 'Setor Awal'
                ]);

                Transaksi::create([
                    'debit' => $this->saldo,
                    'keterangan' => 'tabungan',
                    'ref' => $setor->id
                ]);
            }

            DB::commit();

            $pesan = WhatsappPesan::where('jenis', 'setor')->first();

            if ($nasabah->wa &&  $pesan->status == 'aktif' && $this->saldo > 0)
                $this->whatapps($nasabah, $setor);

            $this->toast(
                type: 'success',
                title: 'Berhasil Menambahkan Data Nasabah',
                description: null,                  // optional (text)
                position: 'toast-top toast-end',    // optional (daisyUI classes)
                icon: 'o-information-circle',       // Optional (any icon)
                css: 'alert-info',                  // Optional (daisyUI classes)
                timeout: 3000,                      // optional (ms)
                redirectTo: Route('nasabah.show', $nasabah->id)                    // optional (uri)
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $this->toast(
                type: 'error',
                title: 'Gagal Menambahkan Data',
                description: null,                  // optional (text)
                position: 'toast-top toast-end',    // optional (daisyUI classes)
                icon: 'o-information-circle',       // Optional (any icon)
                css: 'alert-info',                  // Optional (daisyUI classes)
                timeout: 3000,                      // optional (ms)
                redirectTo: Route('nasabah.show', $nasabah->id)                    // optional (uri)
            );
        }
    }

    public function whatapps($nasabah, $transaksi)
    {



        $wa = WhatsappPesan::where('jenis', 'setor')->first();


        if (!$wa || $wa->status == "tidak")
            return;

        $replace = ['{nama}', '{saldo}', '{jumlah}', '{tanggal}', '{keterangan}'];
        $variable = [
            $nasabah->nama,
            'Rp. ' . number_format($nasabah->saldo, 2, ',', '.'),
            'Rp. ' . number_format($transaksi->setor, 2, ',', '.'),
            date('d-m-Y H:i', strtotime($transaksi->created_at)),
            $transaksi->keterangan ?: '-'

        ];
        $pesan = str_replace($replace, $variable, $wa->pesan);

        $whatsapp = Whatsapp::create([
            'nasabah_id' => $nasabah->id,
            'transaksi_id' => $transaksi->id,
            'pesan' => $pesan,
            'status' => 'pending'
        ]);

        KirimPesanWhatsappJob::dispatch($whatsapp);
    }

    public function updatedfoto()
    {
        $this->validate([
            'foto' => 'image|max:5000|mimes:png,jpeg,bmp,gif', // 1MB Max
        ]);
    }

    public function cari()
    {
        $this->validate([
            'nisn' => ['required'],
        ]);

        $url = env('PPDB_API_URL');
        $key = env('PPDB_API_KEY');
        $nisn = $this->nisn;

        $response = Http::withToken($key)
            ->acceptJson()
            ->get($url . "/santri/{$nisn}");

        if ($response->successful()) {
            $data = $response->json('data');

            $this->nama = $data['nama'];
            $this->tempat_lahir = $data['tempat_lahir'];
            $this->tanggal_lahir = $data['tanggal_lahir'];
            $this->alamat = $data['alamat'];
            $this->telepon = preg_replace('/^0/', '', $data['no_hp']);
            $this->jenis_kelamin = $data['jenis_kelamin'] == 'L' ? 'Laki-Laki' : 'Perempuan';
            $this->nama_wali = $data['nama_ibu'];

            if (!empty($data['foto'])) {
                $imageResponse = Http::withToken($key)->get($data['foto']);

                if ($imageResponse->successful() && strlen($imageResponse->body()) > 0) {
                    $extension = pathinfo(parse_url($data['foto'], PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
                    $filename = Str::random(20) . '.' . $extension;

                    Storage::disk('public')->put("photos/{$filename}", $imageResponse->body());

                    $this->photoUrl = "photos/{$filename}";
                    $this->photo = null;
                } else {
                    // fallback jika gambar gagal diambil
                    $this->photoUrl = null;
                }
            } else {
                // fallback jika link foto kosong
                $this->photoUrl = '/images/default-photo.png';
            }
        } else {
            $this->addError('nisn', 'NISN Tidak Ditemukan');
        }
    }


    public function render()
    {

        $jenis_kelamins = [
            ['id' => null, 'name' => '',],
            ['id' => 'Laki-Laki', 'name' => 'Laki-laki',],
            ['id' => 'Perempuan', 'name' => 'Perempuan',] // <-- this
        ];

        $waSelect = [
            ['id' => 1, 'name' => 'Ya',],
            ['id' => 0, 'name' => 'Tidak',]
        ];

        $dataSaldo = Saldo::all()->prepend((object)[
            'id' => '',
            'nama' => 'Pilih Lembaga'
        ]);

        return view('livewire.nasabah.create', compact('jenis_kelamins', 'waSelect', 'dataSaldo'));
    }
}
