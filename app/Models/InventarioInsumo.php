<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarioInsumo extends Model
{
    protected $table = 'inventario_insumos';

    protected $fillable = [
        'insumo_id',
        'cantidad',
        'costo_unitario',
        'fecha_compra',
        'fecha_caducidad',
        'lote',
        'proveedor',
        'observaciones',
        'cantidad_original',
        'predio_id'
    ];

    protected $casts = [
        'cantidad' => 'float',
        'costo_unitario' => 'float',
        'fecha_compra' => 'date',
        'fecha_caducidad' => 'date'
    ];

    /**
     * Relación con el insumo
     */
    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'insumo_id');
    }

    public function predio()
    {
        return $this->belongsTo(Predios::class, 'predio_id');
    }
}