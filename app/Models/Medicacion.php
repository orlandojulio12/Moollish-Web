<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medicacion extends Model
{
    //
    protected $table = 'medicacion_animal';

    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'fecha_medicacion', //datetime
        'motivo',//Tratamiento curativo, Tratamiento preventivo, Inducidores de celos, Desparasitacion, Transferencia de Embriones, IATF, Quirúrgicos
        'id_animal',
        'observacion',
        'id_veterinario',
        /* Nuevos campos */
        'id_predio',
        'insumo_id',
        'cantidad',
        'dosis',
        'via_administracion',
        'frecuencia',
        '   ',

    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class, 'id_animal');
    }

    public function veterinario()
    {
        return $this->belongsTo(Veterinario::class, 'id_veterinario');
    }

    public function predio()
    {
        return $this->belongsTo(Predios::class, 'id_predio');
    }

    public function insumo()
    {
        return $this->belongsTo(Insumo::class, 'insumo_id');
    }

}
