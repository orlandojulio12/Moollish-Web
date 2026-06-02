<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mk_publicacion_lote_animales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('publicacion_lote_id');
            $table->unsignedBigInteger('animal_id');
            $table->timestamps();

            $table->foreign('publicacion_lote_id')->references('id')->on('mk_publicaciones_lote')->onDelete('cascade');
            $table->foreign('animal_id')->references('id_animal')->on('animales')->onDelete('cascade');

            $table->unique(['publicacion_lote_id', 'animal_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mk_publicacion_lote_animales');
    }
};
