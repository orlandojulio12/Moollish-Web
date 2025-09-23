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
        Schema::create('censo_aves_traspatio', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_predio')->nullable(); 
            $table->foreign('id_predio')->references('id')->on('predios')->onDelete('cascade');
            $table->unsignedBigInteger('id_tipo_ave_transp')->nullable(); 
            $table->foreign('id_tipo_ave_transp')->references('id')->on('tipo_ave_transpatio')->onDelete('set null');
            $table->integer('num_aves')->nullable();
            $table->float('edad')->nullable();
            $table->string('precedencia_aves')->nullable();
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('censo_aves_traspatio');
    }
};
