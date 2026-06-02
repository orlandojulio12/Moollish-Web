<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mk_publicacion_certificados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('publicacion_id');
            $table->enum('tipo_publicacion', ['animal', 'lote']);
            $table->string('nombre_certificado', 150);
            $table->string('url_archivo', 500);
            $table->date('fecha_emision')->nullable();
            $table->timestamps();

            $table->index(['publicacion_id', 'tipo_publicacion'], 'idx_cert_pub_tipo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mk_publicacion_certificados');
    }
};
