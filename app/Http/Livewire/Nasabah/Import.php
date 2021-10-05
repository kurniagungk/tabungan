<?php

namespace App\Http\Livewire\Nasabah;

use App\Imports\NasabahImport;
use Livewire\Component;
use App\Nasabah;
use App\Saldo;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Transaksi;

class Import extends Component
{

    use WithFileUploads;
    public  $file;
    public  $data;


    public function store()
    {
        $validatedData = $this->validate([
            'file' => 'required',
        ]);

        try {


            $excel = Excel::toArray(new NasabahImport, $this->file, null, \Maatwebsite\Excel\Excel::XLSX);
        } catch (\Throwable $t) {
            return $this->addError('file', 'File Rusak, Harap Save As');
        }


        $this->data = $excel[0];

        $validatedData = $this->validate([
            'data.*.no'  => 'unique:nasabah,rekening',
        ]);
    }


    public function save()
    {
        $validatedData = $this->validate([
            'data.*.no'  => 'unique:nasabah,rekening',
        ]);
        try {
            DB::beginTransaction();
            foreach ($this->data as $nsb) {


                $nasabah = Nasabah::create([
                    'rekening' => $nsb['no'],
                    'nama' => $nsb['nama'],
                    'tempat_lahir' => $nsb['alamat'],
                    'tanggal_lahir' => date('Y-m-d'),
                    'alamat' => $nsb['alamat'],
                    'jenis_kelamin' => 'laki-laki',
                    'password' => 1234,
                    'saldo' => 0,

                ]);


                $nasabah->saldo += $nsb['saldo'];

                $nasabah->save();

                $setor =  $nasabah->transaksi()->create([
                    'debit' => $nsb['saldo'],
                    'ref' => 'tabungan',
                    'keterangan' => 'Import tabungan'
                ]);

                $saldo = Saldo::where('nama', 'tabungan')->first();
                $saldo->saldo +=  $nsb['saldo'];
                $saldo->save();


                Transaksi::create([
                    'debit' => $nsb['saldo'],
                    'keterangan' => 'tabungan',
                    'ref' => $setor->id
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return session()->flash('danger', 'Gagal Setor Tunai');;
        }

        session()->flash('pesan', 'Data Nasabah successfully edite.');
        $this->reset('data', 'file');
    }

    public function render()
    {
        return view('livewire.nasabah.import');
    }
}
