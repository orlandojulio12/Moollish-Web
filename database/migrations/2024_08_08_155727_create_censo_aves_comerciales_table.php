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
        Schema::create('censo_aves_comerciales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_predio')->nullable(); 
            $table->foreign('id_predio')->references('id')->on('predios')->onDelete('cascade');
            $table->unsignedBigInteger('id_tipo_ave_comercial')->nullable(); 
            $table->foreign('id_tipo_ave_comercial')->references('id')->on('tipo_ave_comcercial')->onDelete('set null');
            $table->string('linea')->nullable();
            $table->integer('num_aves')->nullable();
            $table->float('edad')->nullable();
            $table->integer('num_galones')->nullable();
            $table->string('area_galones')->nullable();
            $table->float('densidad')->nullable();
            $table->string('tiemp_descan_lotes')->nullable();
            $table->string('procedencia_aves')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('censo_aves_comerciales');
    }
};
