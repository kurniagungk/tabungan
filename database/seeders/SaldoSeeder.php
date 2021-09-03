<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SaldoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('saldo')->insert([
            [
                'nama' => 'tabungan',
                'saldo' => '0',
            ],
            [
                'nama' => 'pinjaman',
                'saldo' => '0',
            ],
        ]);
    }
}
