<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pegawai_id');
            $table->foreign('pegawai_id')->references('id')->on('employees');
            $table->time('jam_masuk');
            $table->time('jam_keluar');
            $table->integer('keterlambatan');
            $table->integer('jam_kerja');
            $table->string('hari', 25);
            $table->date('tanggal');
            $table->string('jenis_presensi', 25);
            $table->integer('status');
            $table->string('status_validasi', 25);
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
        Schema::dropIfExists('presences');
    }
}
