<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMitraTransaksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mitra_transaksi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('mitra_id');
            $table->uuid('nasabah_id');
            $table->uuid('credit')->nullable();
            $table->uuid('debit')->nullable();
            $table->bigInteger('keterangan');
            $table->timestamps();
            $table->foreign('mitra_id')->references('id')->on('mitra')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mitra_transaksi');
    }
}
