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
        Schema::create('censo_ovino_caprino', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_predio')->nullable(); 
            $table->foreign('id_predio')->references('id')->on('predios')->onDelete('cascade');
            $table->integer('men_6_meses_h_ovi')->nullable();
            $table->integer('may_6_meses_h_ovi')->nullable();
            $table->integer('total_hembras_ovinas')->nullable();
            $table->integer('men_6_meses_m_ovi')->nullable();
            $table->integer('may_6_meses_m_ovi')->nullable();
            $table->integer('total_machos_ovi')->nullable();
            $table->integer('total_ovinos')->nullable();
            $table->integer('men_6_meses_h_capri')->nullable();
            $table->integer('may_6_meses_h_capri')->nullable();
            $table->integer('total_hembras_capri')->nullable();
            $table->integer('men_6_meses_m_capri')->nullable();
            $table->integer('may_6_meses_m_capri')->nullable();
            $table->integer('total_machos_capri')->nullable();
            $table->integer('total_caprinos')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('censo_ovino_caprino');
    }
};
