<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PredioParametro extends Model
{
    protected $table = 'predio_parametros';

    protected $fillable = [
        'predio_id',
        'estado_actual_id',
        'estado_nuevo_id',
        'dias_transicion',
    ];

    // Si deseas definir relaciones, por ejemplo:
    public function predio()
    {
        return $this->belongsTo(Predios::class, 'predio_id');
    }

    public function estadoActual()
    {
        return $this->belongsTo(EstadoProductivo::class, 'estado_actual_id');
    }

    public function estadoNuevo()
    {
        return $this->belongsTo(EstadoProductivo::class, 'estado_nuevo_id');
    }
}
