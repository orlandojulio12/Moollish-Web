<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParametroDiasGestacion extends Model
{
    protected $table = 'parametro_dias_gestacion';

    protected $fillable = [
        'predio_id',
        'dias_gestacion',
    ];

    // Valor por defecto para días de gestación
    protected $attributes = [
        'dias_gestacion' => 280,
    ];

    // Relación con el predio
    public function predio()
    {
        return $this->belongsTo(Predios::class, 'predio_id');
    }
}
