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
        Schema::create('servicios_ambientales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_predio')->nullable(); 
            $table->foreign('id_predio')->references('id')->on('predios')->onDelete('cascade');
            $table->unsignedBigInteger('id_tip_servicio')->nullable(); 
            $table->foreign('id_tip_servicio')->references('id')->on('tp_servicio_ambient')->onDelete('set null');
            $table->integer('hectareas')->nullable();
            $table->string('materiales_establecidos')->nullable();
            $table->float('sum_total')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios_ambientales');
    }
};
