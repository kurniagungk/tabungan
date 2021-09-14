<?php

namespace Database\Seeders;

use App\Saldo;
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
        $this->call(Saldo::class);
    }
}
