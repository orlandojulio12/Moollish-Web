<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InforEpidemiologica extends Model
{
    use HasFactory;
    protected $table = 'infor_epidemiologica';
    protected $fillable = [
        'id_predio',
        'anim_enferm_control',
        'anim_enferm_control_cant',
        'cuadr_clinc_sospec',
        'especies_afectadas',
        'toma_muestra',
        'toma_muestra_tipos',
        'toma_muestra_numeros'
    ];
    protected $primaryKey = 'id';
    public function predio()
    {
        return $this->belongsTo(\App\Models\Predios::class, 'id_predio');
    }
}
