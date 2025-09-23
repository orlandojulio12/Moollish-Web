<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoTierraAgua extends Model
{
    use HasFactory;
    protected $table = 'info_tierra_agua';
    protected $fillable = [
        'id_predio',
        'suelos_predominantes',
        'drenaje',
        'manejo_cuencas_nac_agua',
        'cantidad_preservacion',
        'porcentaje_preservacion',
        'fuente_calidad_agua',
        'fuente_calidad_agua_uso_domestic',
        'disp_agua_durant_veran_anim',
        'disp_agua_durant_veran_anim_fuente',
        'disp_agua_durant_veran_riesg',
        'disp_agua_durant_veran_riesg_fuente',
    ];
    protected $primaryKey = 'id';
    public function Predios()
    {
        return $this->belongsTo(Predios::class, 'id_predio');
    }
}
