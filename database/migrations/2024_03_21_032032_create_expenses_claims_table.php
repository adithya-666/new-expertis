<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses_claims', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('presensi_id');
            $table->foreign('presensi_id')->references('id')->on('presences');
            $table->unsignedBigInteger('pegawai_id');
            $table->foreign('pegawai_id')->references('id')->on('employees');
            $table->string('hari', 25);
            $table->date('tanggal');
            $table->integer('uang_makan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expenses_claims');
    }
}
