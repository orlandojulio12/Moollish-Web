<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GestionInformacion extends Model
{
    use HasFactory;
    protected $table = 'gestion_informacion';
    
    protected $fillable = [
        'id_predio',
        'donde_regis_info_finca',
        'los_registros_son',
        'calcula_indicadores',
        'calcula_indicadores_de',
        'calcula_indicadores_de_para',
        'la_informacion_es',
        'utiliza_software_monitore',
        'utiliza_software_monitore_cual',
    ];
    protected $primaryKey = 'id';
    public function Predios()
    {
        return $this->belongsTo(Predios::class, 'id_predio');
    }
}
