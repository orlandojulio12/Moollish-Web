<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiciosAmbientales extends Model
{
    use HasFactory;
    protected $table = 'servicios_ambientales';

    protected $fillable = [
        'id_predio',
        'id_tip_servicio',
        'hectareas',
        'materiales_establecidos',
        'sum_total'
    ];
    public function predio()
    {
        return $this->belongsTo(\App\Models\Predios::class, 'id_predio');
    }
    public function tpserviciosAmbientales()
    {
        return $this->belongsTo(\App\Models\TpServicioAmbientales::class, 'id_tip_servicio');
    }
}
