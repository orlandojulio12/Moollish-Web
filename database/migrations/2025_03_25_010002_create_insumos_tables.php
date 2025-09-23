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
        // Verificar si las tablas ya existen y eliminarlas si es necesario
        if (Schema::hasTable('aplicaciones_insumos')) {
            Schema::dropIfExists('aplicaciones_insumos');
        }
        if (Schema::hasTable('movimientos_insumos')) {
            Schema::dropIfExists('movimientos_insumos');
        }
        if (Schema::hasTable('inventario_insumos')) {
            Schema::dropIfExists('inventario_insumos');
        }
        if (Schema::hasTable('usos_insumos')) {
            Schema::dropIfExists('usos_insumos');
        }
        if (Schema::hasTable('insumos')) {
            Schema::dropIfExists('insumos');
        }
        if (Schema::hasTable('tipos_usos_insumos')) {
            Schema::dropIfExists('tipos_usos_insumos');
        }
        if (Schema::hasTable('categorias_insumos')) {
            Schema::dropIfExists('categorias_insumos');
        }

        // Tabla de categorías de insumos
        Schema::create('categorias_insumos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabla de tipos de usos para los insumos
        Schema::create('tipos_usos_insumos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->unsignedBigInteger('categoria_insumo_id');
            $table->foreign('categoria_insumo_id')->references('id')->on('categorias_insumos');
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabla principal de insumos
        Schema::create('insumos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 50)->unique();
            $table->string('nombre_comercial', 150);
            $table->string('referencia', 100)->nullable();
            $table->unsignedBigInteger('categoria_id');
            $table->foreign('categoria_id')->references('id')->on('categorias_insumos');
            $table->text('descripcion')->nullable();
            $table->string('unidad_medida', 50); // Ej: cc, ml, kg, unidad, etc.
            $table->boolean('requiere_receta')->default(false);
            $table->unsignedBigInteger('predio_id');
            $table->foreign('predio_id')->references('id')->on('predios')->onDelete('cascade');
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->boolean('activo')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // Tabla de usos específicos de cada insumo
        Schema::create('usos_insumos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('insumo_id');
            $table->foreign('insumo_id')->references('id')->on('insumos')->onDelete('cascade');
            $table->unsignedBigInteger('tipo_uso_id');
            $table->foreign('tipo_uso_id')->references('id')->on('tipos_usos_insumos')->onDelete('cascade');
            $table->text('instrucciones')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        // Tabla para el inventario de insumos
        Schema::create('inventario_insumos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('insumo_id');
            $table->foreign('insumo_id')->references('id')->on('insumos')->onDelete('cascade');
            $table->decimal('cantidad', 10, 2);
            $table->decimal('costo_unitario', 12, 2);
            $table->date('fecha_compra');
            $table->date('fecha_caducidad')->nullable();
            $table->string('lote', 100)->nullable();
            $table->string('proveedor', 150)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });

        // Tabla para registrar movimientos de insumos (entradas, salidas, ajustes)
        Schema::create('movimientos_insumos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('insumo_id');
            $table->foreign('insumo_id')->references('id')->on('insumos')->onDelete('cascade');
            $table->enum('tipo_movimiento', ['entrada', 'salida', 'ajuste']);
            $table->decimal('cantidad', 10, 2);
            $table->decimal('costo_unitario', 12, 2)->nullable();
            $table->decimal('costo_total', 12, 2)->nullable();
            $table->date('fecha_movimiento');
            $table->text('motivo')->nullable();
            $table->unsignedBigInteger('predio_id');
            $table->foreign('predio_id')->references('id')->on('predios')->onDelete('cascade');
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Tabla de aplicación de insumos a animales
        Schema::create('aplicaciones_insumos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('insumo_id');
            $table->foreign('insumo_id')->references('id')->on('insumos')->onDelete('cascade');
            $table->unsignedBigInteger('animal_id')->nullable();
            $table->foreign('animal_id')->references('id')->on('animal')->onDelete('set null');
            $table->unsignedBigInteger('potrero_id')->nullable();
            $table->foreign('potrero_id')->references('id')->on('potrero')->onDelete('set null');
            $table->unsignedBigInteger('lote_id')->nullable();
            $table->foreign('lote_id')->references('id')->on('lote')->onDelete('set null');
            $table->decimal('cantidad_aplicada', 10, 2);
            $table->date('fecha_aplicacion');
            $table->time('hora_aplicacion')->nullable();
            $table->string('via_administracion', 100)->nullable(); // oral, intramuscular, etc.
            $table->unsignedBigInteger('tipo_uso_id');
            $table->foreign('tipo_uso_id')->references('id')->on('tipos_usos_insumos')->onDelete('cascade');
            $table->unsignedBigInteger('responsable_id');
            $table->foreign('responsable_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aplicaciones_insumos');
        Schema::dropIfExists('movimientos_insumos');
        Schema::dropIfExists('inventario_insumos');
        Schema::dropIfExists('usos_insumos');
        Schema::dropIfExists('insumos');
        Schema::dropIfExists('tipos_usos_insumos');
        Schema::dropIfExists('categorias_insumos');
    }
};
