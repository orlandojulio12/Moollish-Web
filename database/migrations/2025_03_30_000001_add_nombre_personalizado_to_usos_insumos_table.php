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
        if (Schema::hasTable('usos_insumos')) {
            Schema::table('usos_insumos', function (Blueprint $table) {
                if (!Schema::hasColumn('usos_insumos', 'nombre_personalizado')) {
                    $table->string('nombre_personalizado')->nullable()->after('tipo_uso_id');
                }
                if (!Schema::hasColumn('usos_insumos', 'dosis_recomendada')) {
                    $table->decimal('dosis_recomendada', 10, 2)->nullable()->after('nombre_personalizado');
                }
                if (!Schema::hasColumn('usos_insumos', 'descripcion')) {
                    $table->text('descripcion')->nullable()->after('dosis_recomendada');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('usos_insumos')) {
            Schema::table('usos_insumos', function (Blueprint $table) {
                $table->dropColumn(['nombre_personalizado', 'dosis_recomendada', 'descripcion']);
            });
        }
    }
};
