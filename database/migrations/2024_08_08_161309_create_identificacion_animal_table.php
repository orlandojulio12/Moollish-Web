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
        Schema::create('identificacion_animal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_predio')->nullable(); 
            $table->foreign('id_predio')->references('id')->on('predios')->onDelete('cascade');
            $table->string('visit_anim_con_identif')->nullable();
            $table->integer('porcinos_con')->nullable();
            $table->integer('porcinos_sin')->nullable();
            $table->integer('total_porcinos')->nullable();
            $table->integer('bovinos_con')->nullable();
            $table->integer('bovinos_sin')->nullable();
            $table->integer('total_bovinos')->nullable();
            $table->integer('bufalinos_con')->nullable();
            $table->integer('bufalinos_sin')->nullable();
            $table->integer('total_bufalinos')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('identificacion_animal');
    }
};
