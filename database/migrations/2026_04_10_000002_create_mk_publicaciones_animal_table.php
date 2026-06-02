<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mk_publicaciones_animal', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('animal_id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('precio_venta', 12, 2);
            $table->enum('proposito', ['engorde', 'leche', 'reproduccion', 'sacrificio', 'cria']);
            $table->string('categoria_animal', 100)->nullable(); // Toro adulto, Novilla, Ternero
            $table->tinyInteger('condicion_corporal')->nullable(); // Escala 1-5
            $table->string('temperamento', 50)->nullable(); // Manso/Nervioso/Bravo
            $table->boolean('castrado')->default(false);
            $table->string('marcas_señales', 255)->nullable();
            $table->string('procedencia', 100)->nullable(); // Ganadería propia
            $table->string('estado_sanitario', 50)->nullable(); // Al día
            $table->string('video_youtube', 500)->nullable();
            $table->enum('estado', ['activo', 'vendido', 'pausado', 'eliminado'])->default('activo');
            $table->boolean('destacado')->default(false);
            $table->timestamps();

            $table->foreign('animal_id')->references('id_animal')->on('animales')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mk_publicaciones_animal');
    }
};
