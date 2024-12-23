<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bahan_baku', function (Blueprint $table) {
            $table->id('bahan_baku_id');
            $table->string('kode_bahan_baku', 100);
            $table->string('nama_bahan_baku', 100);
            $table->string('satuan', 100);
            $table->integer('stok_minimal');
            $table->unsignedBigInteger('dibuat_oleh')->nullable();
            $table->foreign('dibuat_oleh')
                ->references('pengguna_id')
                ->on('pengguna')
                ->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bahan_baku');
    }
};
