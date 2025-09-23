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
        Schema::create('man_gen_ganado', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_predio')->nullable(); 
            $table->foreign('id_predio')->references('id')->on('predios')->onDelete('cascade');
            $table->unsignedBigInteger('id_raza_gan')->nullable(); 
            $table->foreign('id_raza_gan')->references('id')->on('razas_ganado')->onDelete('cascade');
            $table->string('ident_animales')->nullable();
            $table->string('sistema_cria_ternero')->nullable();
            $table->string('aliment_ternero')->nullable();
            $table->string('sistem_levant_animal')->nullable();
            $table->string('manej_hembras_prox')->nullable();
            $table->string('manej_vacas_secas')->nullable();
            $table->string('tipo_ordeño')->nullable();
            $table->string('sistem_servic_reproduct')->nullable();
            $table->string('form_program_servicios')->nullable();
            $table->string('pesaje_animal')->nullable();
            $table->string('cuantos_animal_pesa')->nullable();
            $table->string('control_parasito_extern')->nullable();
            $table->string('control_parasito_extern_produc')->nullable();
            $table->string('control_parasito_extern_frecuenc')->nullable();
            $table->string('control_parasito_intern')->nullable();
            $table->string('control_parasito_intern_produc')->nullable();
            $table->string('control_parasito_intern_frecuenc')->nullable();
            $table->string('sumin_sal')->nullable();
            $table->string('a_sal_add_premezcla')->nullable();
            $table->string('a_sal_add_premezcla_especifique')->nullable();
            $table->string('como_manej_ganad_veran')->nullable();
            $table->string('como_manej_ganad_invier')->nullable();
            $table->string('r_pesaje_leche_hembr_lactantes')->nullable();
            $table->string('r_pesaje_leche_hembr_periodicidad')->nullable();
            $table->string('suplement_ganad_epoc_criti')->nullable();
            $table->string('suplement_ganad_epoc_criti_con_que')->nullable();
            $table->string('suplement_ganad_epoc_criti_que_lotes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('man_gen_ganado');
    }
};
