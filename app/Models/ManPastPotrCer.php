<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManPastPotrCer extends Model
{
    use HasFactory;
    protected $table = 'man_past_potr_cerc';
    protected $fillable = [
        'id_predio',
        'area_dest_past',
        'r_fertilazion_potreros',
        'r_fertilazion_potreros_produc',
        'r_fertilazion_potreros_cuant_año',
        'presen_plag_enferm',
        'presen_plag_enferm_tipos',
        'r_control_plagas',
        'r_control_plagas_produc',
        'r_control_plagas_cuant_año',
        'r_control_maleza',
        'r_control_maleza_product',
        'r_control_maleza_cuant_año',
        'precencia_heladas',
        'precencia_heladas_intensidad',
        'precencia_heladas_epocas',
        'div_potreros',
        'div_potreros_como',
        'tipo_pastoreo',
        'rotacional_dias_ocupacion',
        'rotacional_dias_descanso',
        'cercas',
        'cercas_puas',
        'cercas_electricas',
        'la_produccion_forraje_suficiente_año',
        'porque',
    ];
    protected $primaryKey = 'id';
    public function propietarios()
    {
        return $this->belongsTo(Predios::class, 'id_predio');
    }
}
