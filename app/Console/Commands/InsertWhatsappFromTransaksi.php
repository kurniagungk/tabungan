<?php

namespace App\Console\Commands;

use App\Models\Whatsapp;
use App\Models\WhatsappPesan;
use Illuminate\Console\Command;
use App\Models\Nasabah_transaksi;

class InsertWhatsappFromTransaksi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:transaksi:insert-whatsapp {--date=}';

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
        $date = $this->option('date');

        $query = Nasabah_transaksi::query()
            ->whereDoesntHave('whatsapp');

        if ($date) {
            $query->whereDate('created_at', '>=', $date);
        }

        $transaksis = $query->get();

        if ($transaksis->isEmpty()) {
            $this->info('Tidak ada transaksi yang perlu dibuatkan WhatsApp.');
            return 0;
        }

        $bar = $this->output->createProgressBar($transaksis->count());
        $bar->start();

        foreach ($transaksis as $transaksi) {
            $nasabah = $transaksi->nasabah;

            if (!$nasabah || !$nasabah->wa) {
                $bar->advance();
                continue;
            }

            $jenis = $transaksi->debit > 0 ? 'tarik' : 'setor';
            $jumlah = $transaksi->debit > 0 ? $transaksi->debit : $transaksi->credit;

            $waTemplate = WhatsappPesan::where('jenis', $jenis)->first();

            if (!$waTemplate || $waTemplate->status === "tidak") {
                $bar->advance();
                continue;
            }

            $replace = ['{nama}', '{saldo}', '{jumlah}', '{tanggal}', '{keterangan}'];
            $variable = [
                $nasabah->nama,
                'Rp. ' . number_format($nasabah->saldo, 2, ',', '.'),
                'Rp. ' . number_format($jumlah, 2, ',', '.'),
                $transaksi->created_at->format('d-m-Y H:i'),
                $transaksi->keterangan ?: '-',
            ];

            $pesan = str_replace($replace, $variable, $waTemplate->pesan);

            Whatsapp::create([
                'nasabah_id' => $nasabah->id,
                'transaksi_id' => $transaksi->id,
                'pesan' => $pesan,
                'status' => 'pending',
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Selesai membuat WhatsApp dari transaksi.');

        return 0;
    }
}
