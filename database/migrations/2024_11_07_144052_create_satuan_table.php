<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('satuan', function (Blueprint $table) {
            $table->id('satuan_id');
            $table->string('nama_satuan', 100);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('satuan');
    }
};
