<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitaPrediosRiesgo extends Model
{
    use HasFactory;
    protected $table = 'visita_predios_riesgo';

    protected $fillable = [
        'id_predio',
        'enferm_baj_vigil',
        'especie',
        'num_anim_inspec',
        'toma_muestras',
        'toma_muestra_tipo',
        'num_muestras'
    ];
    public function predio()
    {
        return $this->belongsTo(\App\Models\Predios::class, 'id_predio');
    }
}
