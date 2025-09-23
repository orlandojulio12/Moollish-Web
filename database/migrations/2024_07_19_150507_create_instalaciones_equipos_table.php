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
        Schema::create('instalaciones_equipos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_predio')->nullable(); 
            $table->foreign('id_predio')->references('id')->on('predios')->onDelete('cascade');
            $table->unsignedBigInteger('id_tipos_equipos')->nullable(); 
            $table->foreign('id_tipos_equipos')->references('id')->on('tipos_instalaciones_equipos')->onDelete('cascade');
            $table->string('si')->nullable();
            $table->string('no')->nullable();
            $table->string('especificar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instalaciones_equipos');
    }
};
