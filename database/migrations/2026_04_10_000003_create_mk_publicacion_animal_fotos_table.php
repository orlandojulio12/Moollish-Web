<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mk_publicacion_animal_fotos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('publicacion_id');
            $table->string('url_foto', 500);
            $table->tinyInteger('orden')->default(1); // 1, 2 o 3
            $table->timestamps();

            $table->foreign('publicacion_id')->references('id')->on('mk_publicaciones_animal')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mk_publicacion_animal_fotos');
    }
};
