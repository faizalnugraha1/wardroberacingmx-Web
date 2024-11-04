<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice', function (Blueprint $table) {
            $table->id();
            $table->string('kode_invoice')->nullable(false);
            $table->unsignedBigInteger('flag_id');
            $table->foreign('flag_id')->references('id')->on('flags');
            $table->unsignedBigInteger('booking_id')->nullable();
            $table->foreign('booking_id')->references('id')->on('booking');
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('jumlah')->default(0);
            $table->string('midtrans_transaksi_id')->nullable();
            $table->string('midtrans_status')->nullable();
            $table->string('midtrans_status_code')->nullable();
            $table->dateTime('midtrans_date')->nullable();
            $table->string('kurir')->nullable();
            $table->string('kurir_service')->nullable();
            $table->string('resi')->nullable();
            $table->text('alamat')->nullable();
            $table->dateTime('expired_date')->nullable();
            $table->dateTime('finish_date')->nullable();
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
        Schema::dropIfExists('invoice');
    }
};
