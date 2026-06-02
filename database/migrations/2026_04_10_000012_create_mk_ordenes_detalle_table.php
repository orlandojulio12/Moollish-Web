<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mk_ordenes_detalle', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('orden_id');
            $table->enum('tipo', ['animal', 'lote', 'insumo']);
            $table->unsignedBigInteger('referencia_id'); // ID del producto comprado
            $table->string('nombre_snapshot', 255); // Nombre del producto al momento de comprar
            $table->decimal('precio_snapshot', 12, 2); // Precio al momento de comprar
            $table->integer('cantidad')->default(1);
            $table->timestamps();

            $table->foreign('orden_id')->references('id')->on('mk_ordenes')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mk_ordenes_detalle');
    }
};
