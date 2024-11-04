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
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable(false);
            $table->string('slug');
            $table->unsignedBigInteger('kategori_id');
            $table->foreign('kategori_id')->references('id')->on('kategori');
            $table->unsignedBigInteger('brand_id')->nullable(true);
            $table->foreign('brand_id')->references('id')->on('brand');
            $table->string('thumbnail')->default('example-image.jpg');
            $table->integer('harga_jual')->nullable(false);
            $table->integer('harga_asal')->default(0);
            $table->integer('stok');
            $table->float('berat')->nullable(false);
            $table->float('rating')->default(0);
            $table->integer('terjual')->default(0);
            $table->integer('total_review')->default(0);
            $table->string('keywords');
            $table->text('deskripsi')->nullable(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('barang');
    }
};
