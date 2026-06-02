<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mk_lote_animales_manual', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('publicacion_lote_id');
            $table->string('nombre', 255)->nullable();
            $table->string('raza', 100)->nullable();
            $table->string('sexo', 20)->nullable();
            $table->integer('peso_kg')->nullable();
            $table->integer('edad_anos')->nullable();
            $table->integer('edad_meses')->nullable();
            $table->string('color', 100)->nullable();
            $table->string('observaciones', 500)->nullable();
            $table->timestamps();

            $table->index('publicacion_lote_id', 'idx_mk_lote_manual_lote');
        });
    }

    public function down()
    {
        Schema::dropIfExists('mk_lote_animales_manual');
    }
};
