<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarazterizacionRiesgo extends Model
{
    use HasFactory;
    protected $table = 'caracterizacion_riesgo';

    protected $fillable = [
        'id_predio',
        'colinda_establecim_riesgo',
        'colinda_establecim_cual',
        'ubica_en_via',
        'alimen_animal',
        'alimen_animl_otro',
        'lavazas_desper_alimen_porc',
        'real_coccion_previa',
        'sacrif_anim_pred',
        'sacrif_anim_pred_periodic',
        'servic_reproduc',
        'servic_reproduc_otro',
        'num_trabajadores',
        'trabajan_otr_explotacion',
        'asistencia_tecnica',
        'asistencia_tecnica_frecuen',
        'atiend_otr_predi',
        'atiend_otr_predi_cual'
    ];

    public function predio()
    {
        return $this->belongsTo(\App\Models\Predios::class, 'id_predio');
    }
}
