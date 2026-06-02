<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class MkOrden extends Model
{
    protected $table = 'mk_ordenes';

    protected $fillable = [
        'comprador_id', 'vendedor_id', 'total', 'estado',
        'tipo_entrega', 'metodo_pago', 'wompi_transaction_id',
        'wompi_status', 'wompi_reference', 'notas',
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    public function comprador()
    {
        return $this->belongsTo(User::class, 'comprador_id');
    }

    public function vendedor()
    {
        return $this->belongsTo(User::class, 'vendedor_id');
    }

    public function detalle()
    {
        return $this->hasMany(MkOrdenDetalle::class, 'orden_id');
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    public function scopeEntregadas($query)
    {
        return $query->where('estado', 'entregado');
    }
}
