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
        Schema::create('predios', function (Blueprint $table) {
            $table->id();
            $table->string('cod_predio');
            $table->string('nombre_predio');
            $table->unsignedBigInteger('id_departamento')->nullable(); 
            $table->foreign('id_departamento')->references('id')->on('municipio')->onDelete('set null');
            $table->string('municipio');
            $table->string('vereda')->nullable();
            $table->string('forma_de_llegar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('predios');
    }
};
