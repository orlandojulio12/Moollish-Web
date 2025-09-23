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
        Schema::create('censo_bovinos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_predio')->nullable(); 
            $table->foreign('id_predio')->references('id')->on('predios')->onDelete('cascade');
            $table->integer('men_3_meses_h')->nullable();
            $table->integer('3_a_9_meses_h')->nullable();
            $table->integer('9_a_12_meses_h')->nullable();
            $table->integer('1_a_2_años_h')->nullable();
            $table->integer('2_a_3_años_h')->nullable();
            $table->integer('3_a_5_años_h')->nullable();
            $table->integer('may_5_años_h')->nullable();
            $table->integer('total_hembras')->nullable();
            $table->integer('men_3_meses_m')->nullable();
            $table->integer('3_a_9_meses_m')->nullable();
            $table->integer('9_a_12_meses_m')->nullable();
            $table->integer('1_a_2_años_m')->nullable();
            $table->integer('2_a_3_años_m')->nullable();
            $table->integer('may_3_años')->nullable();
            $table->integer('total_machos')->nullable();
            $table->integer('total_bovinos')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('censo_bovinos');
    }
};
