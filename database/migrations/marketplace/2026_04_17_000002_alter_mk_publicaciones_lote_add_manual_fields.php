<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('mk_publicaciones_lote', function (Blueprint $table) {
            $table->enum('origen', ['sistema', 'manual'])->default('manual')->after('user_id');
            $table->integer('m_num_animales')->nullable()->after('origen');
            $table->decimal('m_peso_promedio', 8, 2)->nullable()->after('m_num_animales');
            $table->decimal('m_edad_promedio', 8, 2)->nullable()->after('m_peso_promedio');
            $table->string('m_raza_predominante', 100)->nullable()->after('m_edad_promedio');
            $table->string('m_departamento', 100)->nullable()->after('m_raza_predominante');
            $table->string('m_municipio', 100)->nullable()->after('m_departamento');
        });
    }

    public function down()
    {
        Schema::table('mk_publicaciones_lote', function (Blueprint $table) {
            $table->dropColumn([
                'origen',
                'm_num_animales', 'm_peso_promedio', 'm_edad_promedio',
                'm_raza_predominante', 'm_departamento', 'm_municipio',
            ]);
        });
    }
};
