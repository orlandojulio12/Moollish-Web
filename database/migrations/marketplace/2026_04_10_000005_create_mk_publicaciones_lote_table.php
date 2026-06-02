<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mk_publicaciones_lote', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('nombre', 255);
            $table->text('descripcion')->nullable();
            $table->enum('precio_tipo', ['por_cabeza', 'total'])->default('total');
            $table->decimal('precio', 12, 2);
            $table->enum('proposito', ['engorde', 'leche', 'reproduccion', 'mixto']);
            $table->string('punto_entrega', 255)->nullable();
            $table->string('responsable_flete', 150)->nullable();
            $table->string('guia_movilizacion', 150)->nullable();
            $table->string('tiempo_retiro', 100)->nullable();
            $table->boolean('acepta_visita')->default(true);
            $table->text('condicion_pago')->nullable();
            $table->string('video_youtube', 500)->nullable();
            $table->enum('estado', ['activo', 'vendido', 'pausado'])->default('activo');
            $table->boolean('destacado')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mk_publicaciones_lote');
    }
};
