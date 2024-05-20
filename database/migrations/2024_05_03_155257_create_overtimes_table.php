<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOvertimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('overtimes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pegawai_id');
            $table->foreign('pegawai_id')->references('id')->on('employees')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('presensi_id');
            $table->foreign('presensi_id')->references('id')->on('presences')->onDelete('cascade')->onUpdate('cascade');
            $table->date('date')->nullable();
            $table->string('commander')->nullable();
            $table->string('location')->nullable();
            $table->string('description')->nullable();
            $table->time('start_time');
            $table->time('end_time');
            $table->string('overtime');
            $table->string('overtime_cost');
            $table->string('status');
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
        Schema::dropIfExists('overtimes');
    }
}
