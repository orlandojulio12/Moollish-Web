<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Model;

class MkOrdenDetalle extends Model
{
    protected $table = 'mk_ordenes_detalle';

    protected $fillable = [
        'orden_id', 'tipo', 'referencia_id',
        'nombre_snapshot', 'precio_snapshot', 'cantidad',
    ];

    protected $casts = [
        'precio_snapshot' => 'decimal:2',
        'cantidad'        => 'integer',
    ];

    public function orden()
    {
        return $this->belongsTo(MkOrden::class, 'orden_id');
    }

    public function getSubtotalAttribute()
    {
        return $this->precio_snapshot * $this->cantidad;
    }
}
