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
        Schema::create('caracterizacion_riesgo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_predio')->nullable(); 
            $table->foreign('id_predio')->references('id')->on('predios')->onDelete('cascade');
            $table->string('colinda_establecim_riesgo')->nullable();
            $table->string('colinda_establecim_cual')->nullable();
            $table->string('ubica_en_via')->nullable();
            $table->string('alimen_animal')->nullable();
            $table->string('alimen_animl_otro')->nullable();
            $table->string('lavazas_desper_alimen_porc')->nullable();
            $table->string('real_coccion_previa')->nullable();
            $table->string('sacrif_anim_pred')->nullable();
            $table->string('sacrif_anim_pred_periodic')->nullable();
            $table->string('servic_reproduc')->nullable();
            $table->string('servic_reproduc_otro')->nullable();
            $table->integer('num_trabajadores')->nullable();
            $table->string('trabajan_otr_explotacion')->nullable();
            $table->string('asistencia_tecnica')->nullable();
            $table->string('asistencia_tecnica_frecuen')->nullable();
            $table->string('atiend_otr_predi')->nullable();
            $table->string('atiend_otr_predi_cual')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caracterizacion_riesgo');
    }
};
