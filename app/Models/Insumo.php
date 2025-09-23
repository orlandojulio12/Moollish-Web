<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Insumo extends Model
{
    use SoftDeletes;

    protected $table = 'insumos';

    protected $fillable = [
        'codigo',
        'nombre_comercial',
        'nombre_generico',
        'referencia',
        'categoria_id',
        'descripcion',
        'unidad_medida',
        'requiere_receta',
        'predio_id',
        'precio_referencia',
        'fabricante',
        'plan_cuenta',
        'registro_ica',
        'principio_activo',
        'tiempo_retiro_leche',
        'tiempo_retiro_carne',
        'observaciones',
        'activo',
        'created_by'
    ];

    protected $casts = [
        'requiere_receta' => 'boolean',
        'activo' => 'boolean',
        'precio_referencia' => 'float',
        'tiempo_retiro_leche' => 'integer',
        'tiempo_retiro_carne' => 'integer'
    ];

    /**
     * Relación con la categoría del insumo
     */
    public function categoria()
    {
        return $this->belongsTo(CategoriaInsumo::class, 'categoria_id');
    }

    /**
     * Relación con el predio al que pertenece
     */
    public function predio()
    {
        return $this->belongsTo(Predios::class, 'predio_id');
    }

    /**
     * Relación con el usuario que lo creó
     */
    public function creadoPor()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relación con los usos del insumo
     */
    public function usos()
    {
        return $this->hasMany(UsoInsumo::class, 'insumo_id');
    }

    /**
     * Relación con el inventario del insumo
     */
    public function inventario()
    {
        return $this->hasMany(InventarioInsumo::class, 'insumo_id');
    }

    /**
     * Relación con los movimientos del insumo
     */
    public function movimientos()
    {
        return $this->hasMany(MovimientoInsumo::class, 'insumo_id');
    }

    /**
     * Relación con las aplicaciones del insumo
     */
    public function aplicaciones()
    {
        return $this->hasMany(AplicacionInsumo::class, 'insumo_id');
    }

    /**
     * Calcular el stock actual del insumo
     */
    public function stockActual()
    {
        return $this->movimientos()
            ->select(DB::raw('SUM(CASE
                WHEN tipo_movimiento = "entrada" THEN cantidad
                WHEN tipo_movimiento = "salida" THEN -cantidad
                WHEN tipo_movimiento = "ajuste" THEN cantidad
                ELSE 0 END) as stock_actual'))
            ->first()->stock_actual ?? 0;
    }

    public function inventarios()
    {
        return $this->hasMany(Inventario::class, 'insumo_id');
    }
}
