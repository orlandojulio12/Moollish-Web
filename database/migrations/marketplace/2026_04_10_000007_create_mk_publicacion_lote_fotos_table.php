<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mk_publicacion_lote_fotos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('publicacion_lote_id');
            $table->string('url_foto', 500);
            $table->tinyInteger('orden')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mk_publicacion_lote_fotos');
    }
};
