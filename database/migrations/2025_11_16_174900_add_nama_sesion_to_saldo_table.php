<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('saldo', function (Blueprint $table) {
            $table->string('nama_sesion')->nullable();
        });

        DB::table('saldo')->update([
            'nama_sesion' => DB::raw('nama')
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saldo', function (Blueprint $table) {
            $table->dropColumn('nama_sesion');
        });
    }
};
