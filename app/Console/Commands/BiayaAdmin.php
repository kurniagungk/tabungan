<?php

namespace App\Console\Commands;

use App\Biaya;

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
        $nasabah = Nasabah::get();

        $biaya_admin = Setting::where('nama', 'biaya_admin')->first();
        $tanggal = Setting::where('nama', 'biaya_tanggal')->first();

        $biaya_cek = Biaya::whereMonth('created_at', date('m'))->first();


        if (!$biaya_cek)

            try {

                $this->info('Proses ........');

                DB::beginTransaction();

                $biaya = Biaya::create([
                    'tanggal' => date('Y-m-d'),
                    'jumlah' => 0
                ]);

                $saldo = Saldo::where('nama', 'tabungan')->first();

                $jumlah = 0;

                if (date('d') == $tanggal->isi) {
                    foreach ($nasabah as $data) {



                        if ($data->saldo <= 0)
                            continue;

                        if ($data->saldo < $biaya_admin->isi || $data->saldo == $biaya_admin->isi) {


                            $saldo->saldo -= $data->saldo;


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

                        $data->saldo -= $saldo->isi;

                        $data->save();

                        $tarik =  $data->transaksi()->create([
                            'user_id' => Auth::id(),
                            'created_at' => date('Y-m-d H:i:s'),
                            'credit' => $biaya_admin->isi,
                            'ref' => 'biaya admin',
                        ]);


                        $saldo->saldo -= $biaya_admin->isi;
                        $saldo->save();


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

                    $this->info('Berhasil');
                } else {
                    $this->info('Belum waktunya');
                }
            } catch (\Throwable $th) {
                dd($th);
                $this->info('Gagal');
            }

        else
            $this->info('Sudah melakukan penarikan biaya admin');
    }
}
