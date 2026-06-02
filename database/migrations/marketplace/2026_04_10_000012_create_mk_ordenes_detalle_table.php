<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mk_ordenes_detalle', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orden_id');
            $table->enum('tipo', ['animal', 'lote', 'insumo']);
            $table->unsignedBigInteger('referencia_id');
            $table->string('nombre_snapshot', 255);
            $table->decimal('precio_snapshot', 12, 2);
            $table->integer('cantidad')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mk_ordenes_detalle');
    }
};
