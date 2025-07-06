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
use Illuminate\Support\Facades\Auth;

class Setor extends Component
{

    use Toast;

    public $rekening;
    public $nasabah;
    public $setor;
    public $sisa;
    public $tanggal;
    public $keterangan;
    public $modal = false;
    public $transaksi = false;
    public $nasabah_id;
    public $password;


    protected $listeners = ['password' => 'show', 'close' => 'close'];

    protected $rules = [
        'setor' => 'required|min:1|numeric',
        'tanggal' => 'required|date',
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



    public function updatedSetor($value)
    {
        if ($value)
            $this->sisa = $this->nasabah->saldo + $this->setor;
        else
            $this->sisa = $this->nasabah->saldo;
    }

    public function whatapps($nasabah, $transaksi)
    {



        $wa = WhatsappPesan::where('jenis', 'setor')->first();

        if (!$wa || $wa->status == "tidak")
            return;

        $replace = ['{nama}', '{saldo}', '{jumlah}', '{tanggal}'];
        $variable = [
            $nasabah->nama,
            'Rp. ' . number_format($nasabah->saldo, 2, ',', '.'),
            'Rp. ' . number_format($this->setor, 2, ',', '.'),
            date('d-m-Y H:i', strtotime($transaksi->created_at))

        ];
        $pesan = str_replace($replace, $variable, $wa->pesan);

        Whatsapp::create([
            'nasabah_id' => $nasabah->id,
            'pesan' => $pesan,
            'status' => 'pending'
        ]);
    }



    public function save()
    {


        $this->validate();

        $nasabah = $this->nasabah;
        try {

            DB::beginTransaction();

            // Lock nasabah
            $nasabah = Nasabah::where('id', $nasabah->id)->lockForUpdate()->first();
            $nasabah->saldo += $this->setor;
            $nasabah->save();

            $setor =  $nasabah->transaksi()->create([
                'user_id' => Auth::id(),
                'debit' => $this->setor,
                'ref' => 'tabungan',
                'keterangan' => $this->keterangan
            ]);

            // Lock saldo
            $saldo = Saldo::where('id', $nasabah->saldo_id)->lockForUpdate()->first();
            $saldo->saldo += $this->setor;
            $saldo->save();

            Transaksi::create([
                'debit' => $this->setor,
                'keterangan' => 'tabungan',
                'ref' => $setor->id
            ]);

            DB::commit();

            $pesan = WhatsappPesan::where('jenis', 'setor')->first();
            $setting = Setting::where('nama', 'whatsapp_api')->first();

            if ($nasabah->wa &&  $pesan->status == 'aktif' && $setting->isi == 1)
                $this->whatapps($nasabah, $setor);

            $this->reset('transaksi', 'nasabah', 'setor', 'sisa', 'rekening', 'keterangan',  'modal', 'nasabah_id', 'password');
            $this->tanggal = date("Y-m-d\TH:i:s");
            $this->success(
                'Berhasil Setor Tunai',
                timeout: 5000,
                position: 'toast-top toast-end'
            );
            $this->dispatch('start');
        } catch (\Exception $e) {
            DB::rollBack();

            $this->error(
                'Gagal Setor Tunai',
                timeout: 5000,
                position: 'toast-top toast-end '
            );
        }
    }




    public function render()
    {
        return view('livewire.transaksi.setor');
    }
}
