<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNasabahTransaksiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nasabah_transaksi', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('nasabah_id');
            $table->uuid('credit')->default(0);
            $table->uuid('debit')->default(0);
            $table->string('keterangan')->nullable();
            $table->string('ref')->nullable();
            $table->string('ref_id')->nullable();
            $table->timestamps();
            $table->foreign('nasabah_id')->references('id')->on('nasabah')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nasabah_transaksi');
    }
}
