<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportBtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_bt', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('spj_id');
            $table->foreign('spj_id')->references('id')->on('spj')->onDelete('cascade')->onUpdate('cascade');
            $table->string('type_bt')->nullable(true);
            $table->string('nominal_bt')->nullable(true);
            $table->string('nominal_bt_next')->nullable(true);
            $table->string('nominal_transportasi')->nullable(true);
            $table->string('nominal_akomodasi')->nullable(true);
            $table->string('nominal_kemahalan')->nullable(true);
            $table->string('bukti_akomodasi')->nullable(true);
            $table->string('bukti_transportasi')->nullable(true);
            $table->string('total_cost')->nullable(true);
            $table->string('deposit')->nullable(true);
            $table->string('balance')->nullable(true);
            $table->string('tempat_keberangkatan_bt')->nullable(true);
            $table->string('tanggal_keberangkatan_bt')->nullable(true);
            $table->string('waktu_keberangkatan_bt')->nullable(true);
            $table->string('tempat_sampai_bt')->nullable(true);
            $table->string('tanggal_sampai_bt')->nullable(true);
            $table->string('waktu_sampai_bt')->nullable(true);
            $table->string('tempat_kepulangan_bt')->nullable(true);
            $table->string('waktu_kepulangan_bt')->nullable(true);
            $table->string('status_report_manager')->nullable(true);
            $table->string('status_report_hrd')->nullable(true);
            $table->string('status_report_finance')->nullable(true);
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
        Schema::dropIfExists('report_bt');
    }
}
