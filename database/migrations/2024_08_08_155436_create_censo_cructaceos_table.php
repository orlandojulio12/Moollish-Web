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
        Schema::create('censo_cructaceos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_predio')->nullable(); 
            $table->foreign('id_predio')->references('id')->on('predios')->onDelete('cascade');
            $table->unsignedBigInteger('id_tipo_esp_cructac')->nullable(); 
            $table->foreign('id_tipo_esp_cructac')->references('id')->on('tipo_especie_cructaceos')->onDelete('set null');
            $table->integer('nauplinos')->nullable();
            $table->integer('larvicultura')->nullable();
            $table->integer('engorde')->nullable();
            $table->integer('reproductores')->nullable();
            $table->integer('total_especie_cructacio')->nullable();
            $table->integer('total_cructaceos')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('censo_cructaceos');
    }
};
