<?php

namespace Database\Seeders;

use App\Models\Saldo;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(userSeeder::class);
        $this->call(MitraSeeder::class);
        $this->call(SettingTabungan::class);
        $this->call(SaldoSeeder::class);
        $this->call(WarnaSeeder::class);
    }
}
