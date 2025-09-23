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
        Schema::create('censo_equidos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_predio')->nullable(); 
            $table->foreign('id_predio')->references('id')->on('predios')->onDelete('cascade');
            $table->integer('men_6_mese_caballar')->nullable();
            $table->integer('6_12_meses_caballar')->nullable();
            $table->integer('may_1_año_caballar')->nullable();
            $table->integer('total_caballar')->nullable();
            $table->integer('men_6_mese_mular')->nullable();
            $table->integer('6_12_meses_mular')->nullable();
            $table->integer('may_1_año_mular')->nullable();
            $table->integer('total_mular')->nullable();
            $table->integer('men_6_mese_asnal')->nullable();
            $table->integer('6_12_meses_asnal')->nullable();
            $table->integer('may_1_año_asnal')->nullable();
            $table->integer('total_asnal')->nullable();
            $table->integer('total_equidos')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('censo_equidos');
    }
};
