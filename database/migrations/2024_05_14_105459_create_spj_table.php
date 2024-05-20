<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpjTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spj', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade')->onUpdate('cascade');
            $table->string('no_spj');
            $table->string('commander');
            $table->string('executor');
            $table->string('departure');
            $table->string('destination');
            $table->string('work_day');
            $table->string('date_departure');
            $table->string('date_arrived');
            $table->string('transportation');
            $table->string('type_bt');
            $table->string('description');
            $table->string('akomodasi');
            $table->string('kemahalan');
            $table->string('uang_saku');
            $table->string('biaya_transportasi');
            $table->string('total');
            $table->string('status_manager');
            $table->string('status_finance');
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
        Schema::dropIfExists('spj');
    }
}
