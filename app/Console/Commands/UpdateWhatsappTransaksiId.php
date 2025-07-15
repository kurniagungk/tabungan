<?php

namespace App\Console\Commands;

use App\Models\Whatsapp;
use Illuminate\Console\Command;
use App\Models\Nasabah_transaksi;

class UpdateWhatsappTransaksiId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-whatsapp-transaksi-id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $whatsapps = Whatsapp::whereNull('transaksi_id')->get();

        if ($whatsapps->isEmpty()) {
            $this->info('Tidak ada data whatsapp yang perlu diupdate.');
            return 0;
        }

        $bar = $this->output->createProgressBar($whatsapps->count());
        $bar->start();

        foreach ($whatsapps as $wa) {
            // Ambil tanggal dari isi pesan pakai regex
            if (preg_match('/Tanggal:\s*(\d{2}-\d{2}-\d{4})\s*(\d{2}:\d{2})/', $wa->pesan, $matches)) {
                $tanggal = $matches[1]; // 08-07-2025
                $jam = $matches[2];     // 04:29
                $datetime = \Carbon\Carbon::createFromFormat('d-m-Y H:i', "$tanggal $jam");

                // Cari transaksi dengan nasabah_id dan tanggal yang sesuai (range 1 menit)
                $transaksi = Nasabah_transaksi::where('nasabah_id', $wa->nasabah_id)
                    ->whereBetween('created_at', [
                        $datetime->copy()->subMinute(),
                        $datetime->copy()->addMinute()
                    ])
                    ->first();

                if ($transaksi) {
                    $wa->transaksi_id = $transaksi->id;
                    $wa->save();
                }
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Update selesai.');

        return 0;
    }
}
