<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoInsumo extends Model
{
    protected $table = 'movimientos_insumos';

    protected $fillable = [
        'insumo_id',
        'tipo_movimiento',
        'cantidad',
        'costo_unitario',
        'costo_total',
        'fecha_movimiento',
        'motivo',
        'predio_id',
        'created_by'
    ];

    protected $casts = [
        'cantidad' => 'float',
        'costo_unitario' => 'float',
        'costo_total' => 'float',
        'fecha_movimiento' => 'date'
    ];

    /**
     * Relación con el insumo
     */
    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'insumo_id');
    }

    /**
     * Relación con el predio
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
}
