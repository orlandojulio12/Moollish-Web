<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('usos_insumos', function (Blueprint $table) {
            // Cambiar la columna tipo_uso_id para que sea nullable
            $table->unsignedBigInteger('tipo_uso_id')->nullable()->change();

            // Si la clave foránea existe, hay que eliminarla y volverla a crear
            // Primero, obtenemos el nombre de la clave foránea (puede variar)
            $foreignKeys = collect(DB::select("SHOW CREATE TABLE usos_insumos"))->first();
            preg_match('/CONSTRAINT `(.*?)` FOREIGN KEY \(`tipo_uso_id`\)/', $foreignKeys->{'Create Table'}, $matches);
            if (isset($matches[1])) {
                $foreignKeyName = $matches[1];
                $table->dropForeign($foreignKeyName);
                // Volver a crear la clave foránea con onDelete cascade
                $table->foreign('tipo_uso_id')
                      ->references('id')
                      ->on('tipos_usos_insumos')
                      ->onDelete('cascade'); // Mantener cascade si es necesario
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usos_insumos', function (Blueprint $table) {
            // Revertir: Hacer la columna no nullable de nuevo
            // ¡PRECAUCIÓN! Esto fallará si hay filas con tipo_uso_id NULL.
            $table->unsignedBigInteger('tipo_uso_id')->nullable(false)->change();

            // Si la clave foránea se eliminó en up(), volver a crearla sin el nullable()
            // (La lógica exacta para revertir la clave foránea puede variar)
            $foreignKeys = collect(DB::select("SHOW CREATE TABLE usos_insumos"))->first();
            preg_match('/CONSTRAINT `(.*?)` FOREIGN KEY \(`tipo_uso_id`\)/', $foreignKeys->{'Create Table'}, $matches);
            if (isset($matches[1])) {
                $foreignKeyName = $matches[1];
                 $table->dropForeign($foreignKeyName);
                 $table->foreign('tipo_uso_id')
                       ->references('id')
                       ->on('tipos_usos_insumos')
                       ->onDelete('cascade');
            }
        });
    }
};
