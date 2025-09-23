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
        Schema::create('gestion_informacion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_predio')->nullable(); 
            $table->foreign('id_predio')->references('id')->on('predios')->onDelete('cascade');
            $table->string('donde_regis_info_finca');
            $table->string('los_registros_son')->nullable();
            $table->string('calcula_indicadores');
            $table->string('calcula_indicadores_de')->nullable();
            $table->string('calcula_indicadores_de_para')->nullable();
            $table->string('la_informacion_es')->nullable();
            $table->string('utiliza_software_monitore');
            $table->string('utiliza_software_monitore_cual')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gestion_informacion');
    }
};
