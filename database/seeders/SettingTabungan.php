<?php

namespace Database\Seeders;

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
        DB::table('setting')->insert([
            [
                'nama' => 'biaya_tanggal',
                'isi' => '10',
            ],
            [
                'nama' => 'biaya_jumlah',
                'isi' => '5000',
            ],
            [
                'nama' => 'saldo_minimal',
                'isi' => '5000',
            ],
        ]);
    }
}
