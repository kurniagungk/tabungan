<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WarnaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('warna')->insert([
            [
                'code' => 'FFADAD'
            ],
            [
                'code' => 'FFD6A5'
            ],
            [
                'code' => 'FDFFB6'
            ],
            [
                'code' => 'CAFFBF'
            ],
            [
                'code' => '9BF6FF'
            ],
            [
                'code' => 'A0C4FF'
            ],
            [
                'code' => 'BDB2FF'
            ],
            [
                'code' => 'FFC6FF'
            ],
            [
                'code' => '0091AD'
            ],
            [
                'code' => 'FFF4E4'
            ],
            [
                'code' => 'FFF4E4'
            ],
            [
                'code' => 'F7E8A4'
            ],
            [
                'code' => 'F7E8A4'
            ],
            [
                'code' => 'FF57BB'
            ],
            [
                'code' => 'E56399'
            ],
            [
                'code' => 'DE6E4B'
            ],
            [
                'code' => 'F3CA40'
            ],
        ]);
    }
}
