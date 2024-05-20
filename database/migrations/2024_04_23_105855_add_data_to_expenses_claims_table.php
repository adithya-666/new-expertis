<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataToExpensesClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('expenses_claims', function (Blueprint $table) {
            $table->string('transportasi')->nullable();
            $table->string('parkir_tol')->nullable();
            $table->string('lain-lain')->nullable();
            $table->string('bukti_transportasi')->nullable();
            $table->string('bukti_parkir_tol')->nullable();
            $table->string('bukti_lain_lain')->nullable();
            $table->string('status_transportasi')->nullable();
            $table->string('status_parkir_tol')->nullable();
            $table->string('keterangan_transportasi')->nullable();
            $table->string('keterangan_parkir_tol')->nullable();
            $table->string('status_acc_manager')->nullable();
            $table->string('status_acc_hrd')->nullable();
            $table->string('status_acc_finance')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('expenses_claims', function (Blueprint $table) {
            //
        });
    }
}
