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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn("saldo");
            $table->softDeletes();
            $table->unsignedBigInteger("saldo_id")->nullable()->after("password");
            $table->foreign("saldo_id")->references("id")->on("saldo");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign("user_saldo_id_foreign");
            $table->dropColumn("saldo_id");
            $table->string("saldo")->nullable()->after("password");
        });
    }
};
