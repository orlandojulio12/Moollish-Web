<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tipo_explotacion extends Model
{
    use HasFactory;

    protected $table = 'tp_explotacion';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_predio',
        'bovinos',
        'bufalinos',
        'porcinos',
        'equinos',
        'ovinos',
        'caprinos',
        'aves_corral',
        'aves_no_corral',
        'peces',
        'crustaceos',
        'sistem_acuaticos',
        'apicolas',
        'enferm_ovin_capri',
        'enferm_ovin_capri_cual',
        'mortali_x_enfermedad',
        'mortali_x_enfermedad_cual',
        'pre_apic_produc_explot'
    ];

    public function predio()
    {
        return $this->belongsTo(Predios::class, 'id_predio');
    }
}
