<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoleBtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_bt', function (Blueprint $table) {
            $table->id();
            $table->string('jabatan');
            $table->string('type_1');
            $table->string('type_2');
            $table->string('type_3');
            $table->string('type_4');
            $table->string('type_5');
            $table->string('type_1_next');
            $table->string('type_2_next');
            $table->string('type_3_next');
            $table->string('type_4_next');
            $table->string('type_5_next');
            $table->string('kemahalan_2');
            $table->string('kemahalan_3');
            $table->string('akomodasi');
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
        Schema::dropIfExists('role_bt');
    }
}
