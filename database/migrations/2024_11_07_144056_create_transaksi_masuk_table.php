<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transaksi_masuk', function (Blueprint $table) {
            $table->id('transaksi_masuk_id');
            $table->foreignId('bahan_baku_id')
                ->constrained('bahan_baku', 'bahan_baku_id')
                ->onDelete('restrict');
            $table->date('tanggal_transaksi');
            $table->integer('jumlah');
            $table->integer('harga_per_satuan')->nullable();
            $table->string('keterangan', 255)->nullable();
            $table->foreignId('supplier_id')
                ->nullable()
                ->constrained('supplier', 'supplier_id')
                ->onDelete('restrict');
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
        Schema::dropIfExists('transaksi_masuk');
    }
};
