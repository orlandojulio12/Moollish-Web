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
        Schema::create('censo_porcinos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_predio')->nullable(); 
            $table->foreign('id_predio')->references('id')->on('predios')->onDelete('cascade');
            $table->integer('lact_hast_30_dias')->nullable();
            $table->integer('precebo_31_a_60_dias')->nullable();
            $table->integer('lev_ceb_61_180_dias')->nullable();
            $table->integer('reempl_men_8_meses_h')->nullable();
            $table->integer('cria_men_8_meses_h')->nullable();
            $table->integer('macho_reprod_men_6_meses')->nullable();
            $table->integer('total_porcinos')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('censo_porcinos');
    }
};
