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
        Schema::create('tp_explotacion', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_predio')->nullable(); 
            $table->foreign('id_predio')->references('id')->on('predios')->onDelete('cascade');
            $table->string('bovinos')->nullable();
            $table->string('bufalinos')->nullable();
            $table->string('porcinos')->nullable();
            $table->string('equinos')->nullable();
            $table->string('ovinos')->nullable();
            $table->string('caprinos')->nullable();
            $table->string('aves_corral')->nullable();
            $table->string('aves_no_corral')->nullable();
            $table->string('peces')->nullable();
            $table->string('crustaceos')->nullable();
            $table->string('sistem_acuaticos')->nullable();
            $table->string('apicolas')->nullable();
            $table->string('enferm_ovin_capri')->nullable();
            $table->string('enferm_ovin_capri_cual')->nullable();
            $table->string('mortali_x_enfermedad')->nullable();
            $table->string('mortali_x_enfermedad_cual')->nullable();
            $table->string('pre_apic_produc_explot')->nullable();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('tp_explotacion');
    }
};
