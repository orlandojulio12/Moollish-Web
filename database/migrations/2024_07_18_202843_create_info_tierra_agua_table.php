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
        Schema::create('info_tierra_agua', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_predio')->nullable(); 
            $table->foreign('id_predio')->references('id')->on('predios')->onDelete('cascade');
            $table->string('suelos_predominantes')->nullable();
            $table->string('drenaje')->nullable();
            $table->string('manejo_cuencas_nac_agua')->nullable();
            $table->string('cantidad_preservacion')->nullable();
            $table->string('porcentaje_preservacion')->nullable();
            $table->string('fuente_calidad_agua')->nullable();
            $table->string('fuente_calidad_agua_uso_domestic')->nullable();
            $table->string('disp_agua_durant_veran_anim')->nullable();
            $table->string('disp_agua_durant_veran_anim_fuente')->nullable();
            $table->string('disp_agua_durant_veran_riesg')->nullable();
            $table->string('disp_agua_durant_veran_riesg_fuente')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('info_tierra_agua');
    }
};
