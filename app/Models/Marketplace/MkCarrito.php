<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class MkCarrito extends Model
{
    protected $table = 'mk_carrito';

    protected $fillable = [
        'user_id', 'tipo', 'referencia_id',
        'cantidad', 'precio_unitario',
    ];

    protected $casts = [
        'precio_unitario' => 'decimal:2',
        'cantidad'        => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return match($this->tipo) {
            'animal' => MkPublicacionAnimal::find($this->referencia_id),
            'lote'   => MkPublicacionLote::find($this->referencia_id),
            'insumo' => MkInsumo::find($this->referencia_id),
            default  => null,
        };
    }

    public function getSubtotalAttribute()
    {
        return $this->precio_unitario * $this->cantidad;
    }
}
