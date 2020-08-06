<?php

use Illuminate\Database\Seeder;

class WilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = base_path('wilayah.sql');

        DB::unprepared(file_get_contents($sql));
    }
}
