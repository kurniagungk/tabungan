<?php

namespace Database\Seeders;

use App\Models\Saldo;
use App\Models\Setting;
use App\Models\WhatsappPesan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingTabungan extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $settings = [
            ['nama' => 'biaya_tanggal', 'isi' => '10'],
            ['nama' => 'biaya_admin', 'isi' => '500'],
            ['nama' => 'saldo_minimal', 'isi' => '5000'],
            ['nama' => 'socker_io', 'isi' => 'http://localhost:3000/'],
            ['nama' => 'saldo_habis', 'isi' => 'asd'],
            ['nama' => 'whatsapp_api', 'isi' => '0'],
        ];


        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['nama' => $setting['nama']],
                ['isi' => $setting['isi']]
            );
        }

        $dataSaldo = Saldo::with('whatsappPesan', 'setting')->get();

        // dd($dataSaldo);

        foreach ($dataSaldo as $s) {



            $defaultSettings = Setting::whereNull('saldo_id')
                ->get();

            foreach ($defaultSettings as $default) {
                Setting::firstOrCreate([
                    'saldo_id' => $s->id,
                    'nama' => $default->nama,
                ], [

                    'isi' => $default->isi,
                ]);
            }
        }
    }
}
