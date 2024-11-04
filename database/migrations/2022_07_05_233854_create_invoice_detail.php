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
        Schema::create('invoice_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->string('nama')->nullable();
            $table->foreign('invoice_id')->references('id')->on('invoice')->onDelete('cascade');
            $table->unsignedBigInteger('barang_id')->nullable();
            $table->foreign('barang_id')->references('id')->on('barang');
            $table->integer('harga');
            $table->integer('qty');
            $table->integer('subtotal');
            $table->integer('rating')->nullable();
            $table->text('review')->nullable();
            $table->boolean('is_updated')->default(false);
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
        Schema::dropIfExists('invoice_detail');
    }
};
