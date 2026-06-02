<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mk_ordenes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('comprador_id');
            $table->unsignedBigInteger('vendedor_id');
            $table->decimal('total', 12, 2);
            $table->enum('estado', ['pendiente', 'confirmado', 'coordinacion', 'entregado', 'cancelado'])->default('pendiente');
            $table->enum('tipo_entrega', ['finca', 'coordinar', 'envio_domicilio', 'retiro']);
            $table->enum('metodo_pago', ['transferencia', 'pse', 'nequi', 'efectivo']);
            $table->string('wompi_transaction_id', 255)->nullable();
            $table->string('wompi_status', 50)->nullable();
            $table->string('wompi_reference', 100)->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();

            // Sin onDelete cascade para conservar historial de órdenes
            $table->foreign('comprador_id')->references('id')->on('users');
            $table->foreign('vendedor_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mk_ordenes');
    }
};
