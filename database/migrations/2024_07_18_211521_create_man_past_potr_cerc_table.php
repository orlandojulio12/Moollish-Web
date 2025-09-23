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
        Schema::create('man_past_potr_cerc', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_predio')->nullable(); 
            $table->foreign('id_predio')->references('id')->on('predios')->onDelete('cascade');
            $table->string('area_dest_past')->nullable();
            $table->string('r_fertilazion_potreros')->nullable();
            $table->string('r_fertilazion_potreros_produc')->nullable();
            $table->string('r_fertilazion_potreros_cuant_año')->nullable();
            $table->string('presen_plag_enferm')->nullable();
            $table->string('presen_plag_enferm_tipos')->nullable();
            $table->string('r_control_plagas')->nullable();
            $table->string('r_control_plagas_produc')->nullable();
            $table->string('r_control_plagas_cuant_año')->nullable();
            $table->string('r_control_maleza')->nullable();
            $table->string('r_control_maleza_product')->nullable();
            $table->string('r_control_maleza_cuant_año')->nullable();
            $table->string('precencia_heladas')->nullable();
            $table->string('precencia_heladas_intensidad')->nullable();
            $table->string('precencia_heladas_epocas')->nullable();
            $table->string('div_potreros')->nullable();
            $table->string('div_potreros_como')->nullable();
            $table->string('tipo_pastoreo')->nullable();
            $table->string('rotacional_dias_ocupacion')->nullable();
            $table->string('rotacional_dias_descanso')->nullable();
            $table->string('cercas')->nullable();
            $table->string('cercas_puas')->nullable();
            $table->string('cercas_electricas')->nullable();
            $table->string('la_produccion_forraje_suficiente_año')->nullable();
            $table->string('porque')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('man_past_potr_cerc');
    }
};
