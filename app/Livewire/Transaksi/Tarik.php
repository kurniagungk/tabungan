<?php

namespace App\Livewire\Transaksi;

use App\Models\Saldo;
use Mary\Traits\Toast;
use App\Models\Nasabah;
use App\Models\Setting;
use Livewire\Component;
use App\Models\Whatsapp;
use App\Models\Transaksi;
use App\Models\WhatsappPesan;
use Illuminate\Support\Facades\DB;
use App\Jobs\KirimPesanWhatsappJob;
use Illuminate\Support\Facades\Auth;



class Tarik extends Component
{
    use Toast;

    public $rekening;
    public $nasabah;
    public $nasabah_id;
    public $tarik;
    public $sisa;
    public $tanggal;
    public $keterangan;
    public $modal = false;
    public $transaksi = false;

    public $password;


    protected $listeners = ['password' => 'show', 'close' => 'close'];


    protected $rules = [
        'tarik' => 'required|min:1|numeric',
    ];

    public function mount()
    {
        $this->tanggal = date("Y-m-d\TH:i:s");
    }

    public function cekPassword()
    {
        if ($this->password == $this->nasabah->password) {
            $this->transaksi = true;
            $this->modal = false;
            $this->dispatch('transaksi');
            $this->sisa = $this->nasabah->saldo;
        } else
            $this->addError('password', 'Password Salah');
    }

    public function close()
    {
        if ($this->transaksi == false) {
            $this->modal = false;
            $this->transaksi = false;
            $this->reset('nasabah', 'password');
        }
    }

    public function find()
    {
        $this->reset('nasabah');

        $user = auth()->user();
        $admin = $user->hasRole('admin');

        $rekening =  substr($this->rekening, 0, 3) == 'NSB' ||  substr($this->rekening, 0, 3) == 'nsb'  ? $this->rekening : 'NSB' . $this->rekening;

        $nasabah =  Nasabah::when(!$admin, function ($query) use ($user) {
            return $query->where('saldo_id', $user->saldo_id);
        })->where('rekening', $rekening)->where('status', 'aktif')->first();

        if (!$nasabah) {
            $this->reset('nasabah');
            return $this->addError('rekening', 'Data tidak ditemukan');
        }
        $this->nasabah = $nasabah;
        $this->dispatch('modal');
        $this->modal = true;
    }



    public function updatedTarik($value)
    {
        if ($value)
            $this->sisa = $this->nasabah->saldo - $this->tarik;
        else
            $this->sisa = $this->nasabah->saldo;
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

            // Kunci baris nasabah agar tidak bisa diakses proses lain sampai transaksi selesai
            $nasabah = Nasabah::where('id', $nasabah->id)->lockForUpdate()->first();
            $nasabah->saldo -= $this->tarik;
            $nasabah->save();

            $tarik =  $nasabah->transaksi()->create([
                'user_id' => Auth::id(),
                'created_at' => $this->tanggal,
                'credit' => $this->tarik,
                'ref' => 'tabungan',
                'keterangan' => $this->keterangan
            ]);


            Transaksi::create([
                'credit' => $this->tarik,
                'keterangan' => 'tabungan',
                'ref' => $tarik->id
            ]);

            DB::commit();

            $pesan = WhatsappPesan::where('jenis', 'tarik')->first();
            $setting = Setting::where('nama', 'whatsapp_api')->first();

            if ($nasabah->wa &&  $pesan->status == 'aktif' && $setting->isi == 1)
                $this->whatapps($nasabah, $tarik);

            $this->reset('transaksi', 'nasabah', 'tarik', 'sisa', 'rekening', 'keterangan',  'modal', 'nasabah_id', 'password');
            $this->tanggal = date("Y-m-d\TH:i:s");
            $this->success(
                'Berhasil Tarik Tunai',
                timeout: 5000,
                position: 'toast-top toast-end'
            );
        } catch (\Exception $e) {
            DB::rollBack();

            $this->error(
                'Gagal Tarik Tunai',
                timeout: 5000,
                position: 'toast-top toast-end '
            );
        }



        $this->dispatch('start');
    }


    public function whatapps($nasabah, $transaksi)
    {



        $wa = WhatsappPesan::where('jenis', 'tarik')->first();

        if (!$wa || $wa->status == "tidak")
            return;

        $replace = ['{nama}', '{saldo}', '{jumlah}', '{tanggal}', '{keterangan}'];
        $variable = [
            $nasabah->nama,
            'Rp. ' . number_format($nasabah->saldo, 2, ',', '.'),
            'Rp. ' . number_format($this->tarik, 2, ',', '.'),
            date('d-m-Y H:i', strtotime($transaksi->created_at)),
            $this->keterangan ?: '-'

        ];
        $pesan = str_replace($replace, $variable, $wa->pesan);

        $whatsapp =  Whatsapp::create([
            'nasabah_id' => $nasabah->id,
            'transaksi_id' => $transaksi->id,
            'pesan' => $pesan,
            'status' => 'pending'
        ]);

        KirimPesanWhatsappJob::dispatch($whatsapp);
    }



    public function render()
    {
        return view('livewire.transaksi.tarik');
    }
}
