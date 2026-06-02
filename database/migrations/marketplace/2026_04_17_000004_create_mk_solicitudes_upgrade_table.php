<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('mk_solicitudes_upgrade', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->integer('rol_actual');
            $table->integer('rol_solicitado');
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado'])
                  ->default('pendiente');
            $table->text('mensaje')->nullable();
            $table->unsignedBigInteger('respondido_por')->nullable();
            $table->timestamp('respondido_at')->nullable();
            $table->timestamps();

            $table->index('user_id', 'idx_mk_upgrade_user');
            $table->index('estado', 'idx_mk_upgrade_estado');
        });
    }

    public function down()
    {
        Schema::dropIfExists('mk_solicitudes_upgrade');
    }
};
