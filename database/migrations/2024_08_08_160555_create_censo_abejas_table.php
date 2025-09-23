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
        Schema::create('censo_abejas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_predio')->nullable(); 
            $table->foreign('id_predio')->references('id')->on('predios')->onDelete('cascade');
            $table->unsignedBigInteger('id_tipo_abejas')->nullable(); 
            $table->foreign('id_tipo_abejas')->references('id')->on('tipo_abejas')->onDelete('set null');
            $table->integer('num_apiarios')->nullable();
            $table->integer('num_colmenas')->nullable();
            $table->string('poblacion_estimada')->nullable();
            $table->string('realiz_trashumancia')->nullable();
            $table->string('nom_estable_destino')->nullable();
            $table->integer('id_departamento')->nullable();
            $table->string('municipio')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('censo_abejas');
    }
};
