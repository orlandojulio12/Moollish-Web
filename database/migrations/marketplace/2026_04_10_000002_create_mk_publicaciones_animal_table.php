<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mk_publicaciones_animal', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('animal_id');
            $table->unsignedBigInteger('user_id');
            $table->decimal('precio_venta', 12, 2);
            $table->enum('proposito', ['engorde', 'leche', 'reproduccion', 'sacrificio', 'cria']);
            $table->string('categoria_animal', 100)->nullable();
            $table->tinyInteger('condicion_corporal')->nullable();
            $table->string('temperamento', 50)->nullable();
            $table->boolean('castrado')->default(false);
            $table->string('marcas_senales', 255)->nullable();
            $table->string('procedencia', 100)->nullable();
            $table->string('estado_sanitario', 50)->nullable();
            $table->string('video_youtube', 500)->nullable();
            $table->enum('estado', ['activo', 'vendido', 'pausado', 'eliminado'])->default('activo');
            $table->boolean('destacado')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mk_publicaciones_animal');
    }
};
