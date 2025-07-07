<?php

namespace App\Console\Commands;

use App\Models\Biaya;

use App\Models\Nasabah;

use App\Models\Saldo;
use App\Models\Setting;
use App\Models\Transaksi;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BiayaAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'biaya:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $saldo = Saldo::get();

        foreach ($saldo as $data) {
            $this->biaya($data);
        }
    }

    public function biaya($lembaga)
    {


        $nasabah = Nasabah::where('saldo_id', $lembaga->id)->get();

        $biaya_admin = Setting::where('saldo_id', $lembaga->id)->where('nama', 'biaya_admin')->first();
        $tanggal = Setting::where('saldo_id', $lembaga->id)->where('nama', 'biaya_tanggal')->first();
        $biaya_cek = Biaya::where('saldo_id', $lembaga->id)->whereMonth('created_at', date('m'))->first();

        if ($biaya_admin->isi < 1) {
            $this->info("Biaya admin belum diatur untuk {$lembaga->nama}........");
            return;
        }

        if (!$biaya_cek) {
            try {

                $this->info("Proses {$lembaga->nama}........");

                DB::beginTransaction();

                $biaya = Biaya::create([
                    'tanggal' => date('Y-m-d'),
                    'jumlah' => 0,
                    'saldo_id' => $lembaga->id
                ]);


                $jumlah = 0;

                if (date('d') == $tanggal->isi) {
                    foreach ($nasabah as $data) {



                        if ($data->saldo <= 0)
                            continue;

                        if ($data->saldo < $biaya_admin->isi || $data->saldo == $biaya_admin->isi) {

                            Transaksi::create([
                                'credit' => $data->saldo,
                                'keterangan' => 'biaya admin',
                                'ref' => 'biaya admin',
                                'ref_id' => $biaya->id
                            ]);

                            $jumlah += $data->saldo;

                            $data->saldo -= $data->saldo;
                            $data->status = "tidak";
                            $data->save();



                            continue;
                        }


                        $data->save();

                        $tarik =  $data->transaksi()->create([
                            'user_id' => Auth::id(),
                            'created_at' => date('Y-m-d H:i:s'),
                            'credit' => $biaya_admin->isi,
                            'ref' => 'biaya admin',
                        ]);




                        Transaksi::create([
                            'credit' => $biaya_admin->isi,
                            'keterangan' => 'biaya admin',
                            'ref' => $biaya->id
                        ]);

                        $jumlah += $biaya_admin->isi;
                    }

                    $biaya->jumlah = $jumlah;
                    $biaya->save();

                    DB::commit();

                    $this->info("Berhasil {$lembaga->nama}........");
                } else {
                    $this->info("Belum waktunya {$lembaga->nama}........");
                }
            } catch (\Throwable $th) {
                DB::rollBack();
                $this->info("Gagal {$lembaga->nama}........");
            }
        } else
            $this->info('Sudah melakukan penarikan biaya admin');
    }
}
