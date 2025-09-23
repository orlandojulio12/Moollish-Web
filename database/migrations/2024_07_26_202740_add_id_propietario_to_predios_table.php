<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('predios', function (Blueprint $table) {
            $table->unsignedBigInteger('id_propietario')->nullable()->after('id'); // Añadir el campo id_propietario

            // Establecer la relación de clave foránea
            $table->foreign('id_propietario')
                  ->references('id')
                  ->on('propietarios')
                  ->onDelete('set null'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('predios', function (Blueprint $table) {
            // Eliminar la relación de clave foránea
            $table->dropForeign(['id_propietario']);
            $table->dropColumn('id_propietario');
        });
    }
};
