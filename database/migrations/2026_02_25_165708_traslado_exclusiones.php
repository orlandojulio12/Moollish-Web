<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla que registra animales excluidos de un traslado masivo.
     *
     * Cuando se hace un traslado de 100 animales y se excluyen 2,
     * los 98 van a movimientos_animales y los 2 van aquí con su motivo.
     * Así la trazabilidad queda completa: se sabe quién fue, quién no fue, y por qué.
     */
    public function up(): void
    {
        Schema::create('traslado_exclusiones', function (Blueprint $table) {
            $table->id();

            // El animal que NO fue trasladado
            $table->unsignedBigInteger('animal_id')
                  ->comment('Animal que fue excluido del traslado');

            // Código único del traslado masivo al que pertenece esta exclusión.
            // Formato: TM-{timestamp}-{userId}
            // Permite agrupar "todos los excluidos de este traslado"
            $table->string('traslado_ref', 64)
                  ->nullable()
                  ->comment('Código del traslado masivo de referencia');

            // Dónde QUEDÓ el animal (no se movió)
            $table->unsignedBigInteger('predio_id')
                  ->nullable()
                  ->comment('Predio donde quedó el animal');

            $table->unsignedBigInteger('lote_id')
                  ->nullable()
                  ->comment('Lote donde quedó el animal');

            $table->unsignedBigInteger('potrero_id')
                  ->nullable()
                  ->comment('Potrero donde quedó el animal');

            // Por qué no fue trasladado
            $table->text('motivo')
                  ->comment('Razón por la que este animal no fue trasladado');

            $table->date('fecha')
                  ->comment('Fecha del traslado del que fue excluido');

            $table->timestamps();

            // Índices
            $table->index('animal_id',      'idx_te_animal');
            $table->index('traslado_ref',   'idx_te_ref');
            $table->index('predio_id',      'idx_te_predio');
            $table->index('fecha',          'idx_te_fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('traslado_exclusiones');
    }
};