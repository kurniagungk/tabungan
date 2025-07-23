<?php

namespace App\Console\Commands;

use App\Models\Biaya;

use App\Models\Saldo;

use App\Models\Nasabah;
use App\Models\Setting;
use App\Models\Whatsapp;
use App\Models\Transaksi;
use App\Models\WhatsappPesan;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Jobs\KirimPesanWhatsappJob;
use Illuminate\Support\Facades\Auth;

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
        $today = Carbon::today();

        $nasabahList = Nasabah::where('saldo_id', $lembaga->id)->get();
        $biayaAdminSetting = Setting::where('saldo_id', $lembaga->id)->where('nama', 'biaya_admin')->first();
        $tanggalSetting = Setting::where('saldo_id', $lembaga->id)->where('nama', 'biaya_tanggal')->first();

        if (!$biayaAdminSetting || $biayaAdminSetting->isi < 1) {
            $this->info("Biaya admin belum diatur untuk {$lembaga->nama}.");
            return;
        }

        if (!$tanggalSetting || $today->day != intval($tanggalSetting->isi)) {
            $this->info("Belum waktunya penarikan biaya admin untuk {$lembaga->nama}.");
            return;
        }

        $biayaExists = Biaya::where('saldo_id', $lembaga->id)
            ->whereDate('created_at', $today)
            ->exists();

        if ($biayaExists) {
            $this->info("Sudah melakukan penarikan biaya admin hari ini untuk {$lembaga->nama}.");
            return;
        }

        try {
            $this->info("Proses penarikan biaya admin {$lembaga->nama} dimulai...");

            DB::beginTransaction();

            $biaya = Biaya::create([
                'tanggal' => $today->toDateString(),
                'jumlah' => 0,
                'saldo_id' => $lembaga->id
            ]);

            $totalBiaya = 0;
            $userId = Auth::id();

            $pesan = WhatsappPesan::where('jenis', 'setor')->first();
            $setting = Setting::where('nama', 'whatsapp_api')->first();

            foreach ($nasabahList as $nasabah) {
                $saldo = $nasabah->saldo;

                if ($saldo <= 0) continue;

                $nominal = $saldo <= $biayaAdminSetting->isi ? $saldo : $biayaAdminSetting->isi;
                $totalBiaya += $nominal;

                $nasabah->saldo -= $nominal;

                if ($saldo <= $biayaAdminSetting->isi) {
                    $nasabah->status = 'tidak';
                }

                $setor = $nasabah->transaksi()->create([
                    'user_id' => $userId,
                    'credit' => $nominal,
                    'ref' => 'biaya admin',
                    'keterangan' => 'biaya admin',
                    'created_at' => now(),
                    'ref_id' => $biaya->id,
                ]);

                $nasabah->save();

                if (
                    $nasabah->wa &&
                    $pesan && $pesan->status === 'aktif' &&
                    $setting && $setting->isi == 1
                ) {
                    $this->whatapps($nasabah, $setor);
                }
            }


            $biaya->update([
                'jumlah' => $totalBiaya
            ]);

            DB::commit();

            $this->info("Berhasil menarik biaya admin {$lembaga->nama} sejumlah Rp" . number_format($totalBiaya));
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->error("Gagal menarik biaya admin untuk {$lembaga->nama}. Error: " . $e->getMessage());
        }
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
            'Rp. ' . number_format($transaksi->tarik, 2, ',', '.'),
            date('d-m-Y H:i', strtotime($transaksi->created_at)),
            $transaksi->keterangan ?: '-'

        ];
        $pesan = str_replace($replace, $variable, $wa->pesan);

        $whatsapp = Whatsapp::create([
            'nasabah_id' => $nasabah->id,
            'pesan' => $pesan,
            'status' => 'pending'
        ]);

        KirimPesanWhatsappJob::dispatch($whatsapp);
    }
}
