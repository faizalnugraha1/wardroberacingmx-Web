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
        Schema::create('booking', function (Blueprint $table) {
            $table->id();
            $table->string('booking_id');
            $table->unsignedBigInteger('flag_id');
            $table->foreign('flag_id')->references('id')->on('flags');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->date('tanggal_booking')->nullable(false);
            $table->time('jam_booking')->nullable(false);
            $table->string('model_motor')->nullable(false);
            $table->string('kebutuhan')->nullable();
            $table->text('keterangan')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('booking');
    }
};
