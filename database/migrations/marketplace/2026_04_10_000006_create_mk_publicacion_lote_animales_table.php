<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mk_publicacion_lote_animales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('publicacion_lote_id');
            $table->unsignedBigInteger('animal_id');
            $table->timestamps();

            $table->unique(['publicacion_lote_id', 'animal_id'], 'uq_lote_animal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mk_publicacion_lote_animales');
    }
};
