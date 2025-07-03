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
        Schema::table('nasabah', function (Blueprint $table) {
            $table->unsignedBigInteger("saldo_id")->nullable()->after("rekening");
            $table->foreign("saldo_id")->references("id")->on("saldo");
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nasabah', function (Blueprint $table) {
            $table->dropForeign("nasabah_saldo_id_foreign");
            $table->dropColumn("saldo_id");
        });
    }
};
