<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mk_insumos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('nombre', 255);
            $table->string('marca', 150)->nullable();
            $table->text('descripcion')->nullable();
            $table->enum('categoria', ['minerales', 'medicamentos', 'alimentos', 'equipos', 'otros']);
            $table->decimal('precio', 12, 2);
            $table->string('unidad', 50); // kg, litro, unidad, caja
            $table->integer('stock')->default(0);
            $table->string('registro_ica', 150)->nullable();
            $table->text('composicion')->nullable();
            $table->text('uso_recomendado')->nullable();
            $table->string('dosis', 255)->nullable();
            $table->string('animales_objetivo', 255)->nullable();
            $table->enum('estado', ['activo', 'agotado', 'pausado'])->default('activo');
            $table->boolean('destacado')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mk_insumos');
    }
};
