<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mk_publicacion_certificados', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('publicacion_id'); // ID del animal o lote
            $table->enum('tipo_publicacion', ['animal', 'lote']);
            $table->string('nombre_certificado', 150); // Brucelosis, TBC, Vacunación, Guía ICA
            $table->string('url_archivo', 500);
            $table->date('fecha_emision')->nullable();
            $table->timestamps();

            // Sin FK estricta: publicacion_id puede referenciar mk_publicaciones_animal o mk_publicaciones_lote
            $table->index(['publicacion_id', 'tipo_publicacion']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mk_publicacion_certificados');
    }
};
