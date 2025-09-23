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
        Schema::create('censo_peces', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_predio')->nullable(); 
            $table->foreign('id_predio')->references('id')->on('predios')->onDelete('cascade');
            $table->unsignedBigInteger('id_tipo_esp_peces')->nullable(); 
            $table->foreign('id_tipo_esp_peces')->references('id')->on('tipo_especie_peces')->onDelete('set null');
            $table->integer('ovas')->nullable();
            $table->integer('alevinos')->nullable();
            $table->integer('engorde')->nullable();
            $table->integer('reproductores')->nullable();
            $table->integer('total_pez_especie')->nullable();
            $table->integer('total_peces')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('censo_peces');
    }
};
