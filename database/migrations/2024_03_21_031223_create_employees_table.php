<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('role_bt_id');
            $table->foreign('role_bt_id')->references('id')->on('role_bt')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nama_pegawai')->nullable(true);
            $table->string('nik_pegawai')->nullable(true);
            $table->string('nomor_kk')->nullable(true);
            $table->string('email')->nullable(true);
            $table->string('email_kantor')->nullable(true);
            $table->string('departemen')->nullable(true);
            $table->string('unit_bisnis')->nullable(true);
            $table->string('golongan')->nullable(true);
            $table->string('jabatan')->nullable(true);
            $table->string('role')->nullable(true);
            $table->string('golongan_asli')->nullable(true);
            $table->string('cabang')->nullable(true);
            $table->string('kendaraan')->nullable(true);
            $table->string('status_pegawai')->nullable(true);
            $table->date('tanggal_masuk')->nullable(true);
            $table->string('status_pernikahan')->nullable(true);
            $table->string('tempat_lahir')->nullable(true);
            $table->date('tanggal_lahir')->nullable(true);
            $table->string('jenis_kelamin')->nullable(true);
            $table->string('golongan_darah')->nullable(true);
            $table->text('alamat')->nullable(true);
            $table->integer('no_telepon')->length(15)->nullable(true);
            $table->string('tingkat_pendidikan')->nullable(true);
            $table->string('status_pendidikan')->nullable(true);
            $table->string('nama_pasangan')->nullable(true);
            $table->string('jumlah_anak')->nullable(true);
            $table->string('nama_ayah')->nullable(true);
            $table->string('no_telepon_keluarga')->nullable(true);
            $table->string('nomor_rekening')->nullable(true);
            $table->string('foto')->nullable(true);
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
        Schema::dropIfExists('employees');
    }
}
