<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('whatapps');


        Schema::create('whatsapp', function (Blueprint $table) {
            $table->ulid("id")->primary();
            $table->uuid('nasabah_id');
            $table->string('pesan');
            $table->enum('status', ['pending', 'berhasil', 'gagal']);
            $table->timestamps();
            $table->foreign('nasabah_id')->references('id')->on('nasabah')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp');

        Schema::create('whatapps', function (Blueprint $table) {
            $table->id();
            $table->string('nomer', 13);
            $table->string('nama')->nullable();
            $table->enum('status', ['berhasil', 'gagal']);
            $table->enum('jenis', ['terima', 'kirim']);
            $table->timestamps();
        });
    }
};
