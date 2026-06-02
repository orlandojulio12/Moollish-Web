<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mk_carrito', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->enum('tipo', ['animal', 'lote', 'insumo']);
            $table->unsignedBigInteger('referencia_id'); // ID del item según el tipo
            $table->integer('cantidad')->default(1); // Solo relevante para insumos
            $table->decimal('precio_unitario', 12, 2); // Precio al momento de agregar
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->index(['user_id', 'tipo', 'referencia_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mk_carrito');
    }
};
