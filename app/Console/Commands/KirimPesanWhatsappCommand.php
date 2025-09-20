<?php

namespace App\Console\Commands;

use App\Models\Whatsapp;
use Illuminate\Console\Command;
use App\Jobs\KirimPesanWhatsappJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class KirimPesanWhatsappCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:whatsapp:kirim {status=pending}';

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

        $this->info('start kirim pesan whatsapp');

        $status = $this->argument('status');

        $whatsapps = Whatsapp::where('status', $status)->get();

        if ($whatsapps->isEmpty()) {
            $this->info('Tidak ada pesan pending.');
            return;
        }

        foreach ($whatsapps as $pesan) {

            try {

                KirimPesanWhatsappJob::dispatch($pesan);
                $pesan->update(['status' => 'pending']);
            } catch (\Exception $e) {

                Log::error($e);
            }
        }
    }
}
