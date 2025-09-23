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
        Schema::create('infor_epidemiologica', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_predio')->nullable(); 
            $table->foreign('id_predio')->references('id')->on('predios')->onDelete('cascade');
            $table->string('anim_enferm_control')->nullable();
            $table->string('anim_enferm_control_cant')->nullable();
            $table->string('cuadr_clinc_sospec')->nullable();
            $table->string('especies_afectadas')->nullable();
            $table->string('toma_muestra')->nullable();
            $table->string('toma_muestra_tipos')->nullable();
            $table->string('toma_muestra_numeros')->nullable();            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infor_epidemiologica');
    }
};
