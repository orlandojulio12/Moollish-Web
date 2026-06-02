<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mk_insumos_fotos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('insumo_id');
            $table->string('url_foto', 500);
            $table->tinyInteger('orden')->default(1);
            $table->timestamps();

            $table->foreign('insumo_id')->references('id')->on('mk_insumos')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mk_insumos_fotos');
    }
};
