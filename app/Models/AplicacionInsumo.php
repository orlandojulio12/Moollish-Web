<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AplicacionInsumo extends Model
{
    protected $table = 'aplicaciones_insumos';

    protected $fillable = [
        'insumo_id',
        'animal_id',
        'potrero_id',
        'lote_id',
        'cantidad_aplicada',
        'fecha_aplicacion',
        'hora_aplicacion',
        'via_administracion',
        'tipo_uso_id',
        'responsable_id',
        'observaciones'
    ];

    protected $casts = [
        'cantidad_aplicada' => 'float',
        'fecha_aplicacion' => 'date',
        'hora_aplicacion' => 'datetime'
    ];

    /**
     * Relación con el insumo
     */
    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'insumo_id');
    }

    /**
     * Relación con el animal
     */
    public function animal()
    {
        return $this->belongsTo(Animal::class, 'animal_id');
    }

    /**
     * Relación con el potrero
     */
    public function potrero()
    {
        return $this->belongsTo(Potrero::class, 'potrero_id');
    }

    /**
     * Relación con el lote
     */
    public function lote()
    {
        return $this->belongsTo(Lote::class, 'lote_id');
    }

    /**
     * Relación con el tipo de uso
     */
    public function tipoUso()
    {
        return $this->belongsTo(TipoUsoInsumo::class, 'tipo_uso_id');
    }

    /**
     * Relación con el responsable
     */
    public function responsable()
    {
        return $this->belongsTo(User::class, 'responsable_id');
    }
}
