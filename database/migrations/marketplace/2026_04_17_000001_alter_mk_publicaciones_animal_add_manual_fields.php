<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('mk_publicaciones_animal', function (Blueprint $table) {
            $table->unsignedBigInteger('animal_id')->nullable()->change();

            $table->enum('origen', ['sistema', 'manual'])->default('manual')->after('animal_id');

            $table->string('m_nombre', 255)->nullable()->after('origen');
            $table->string('m_raza', 100)->nullable()->after('m_nombre');
            $table->string('m_sexo', 20)->nullable()->after('m_raza');
            $table->integer('m_peso_kg')->nullable()->after('m_sexo');
            $table->integer('m_edad_anos')->nullable()->after('m_peso_kg');
            $table->integer('m_edad_meses')->nullable()->after('m_edad_anos');
            $table->string('m_color', 100)->nullable()->after('m_edad_meses');
            $table->string('m_hierro', 100)->nullable()->after('m_color');
            $table->string('m_id_sinigan', 100)->nullable()->after('m_hierro');
            $table->string('m_departamento', 100)->nullable()->after('m_id_sinigan');
            $table->string('m_municipio', 100)->nullable()->after('m_departamento');
        });
    }

    public function down()
    {
        Schema::table('mk_publicaciones_animal', function (Blueprint $table) {
            $table->dropColumn([
                'origen',
                'm_nombre', 'm_raza', 'm_sexo',
                'm_peso_kg', 'm_edad_anos', 'm_edad_meses',
                'm_color', 'm_hierro', 'm_id_sinigan',
                'm_departamento', 'm_municipio',
            ]);
            $table->unsignedBigInteger('animal_id')->nullable(false)->change();
        });
    }
};
