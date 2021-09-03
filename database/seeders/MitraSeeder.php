<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MitraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mitra')->insert([
            'nama' => 'Tabungan',
            'email' => 'admin@admin.com',
            'saldo' => 0,
            'password' => Hash::make('password'),
        ]);

        DB::table('mitra')->insert([
            'nama' => 'Waserda',
            'email' => 'waserda@admin.com',
            'saldo' => 0,
            'password' => Hash::make('password'),
        ]);
    }
}
