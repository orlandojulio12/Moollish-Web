<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('visita_predios_riesgo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_predio')->nullable(); 
            $table->foreign('id_predio')->references('id')->on('predios')->onDelete('cascade');
            $table->string('enferm_baj_vigil')->nullable();
            $table->string('especie')->nullable();
            $table->string('num_anim_inspec')->nullable();
            $table->string('toma_muestras')->nullable();
            $table->string('toma_muestra_tipo')->nullable();
            $table->integer('num_muestras')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visita_predios_riesgo');
    }
};
