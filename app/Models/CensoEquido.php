<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CensoEquido extends Model
{
    use HasFactory;

    protected $table = 'censo_equidos';

    protected $fillable = [
        'id_predio',
        'men_6_mese_caballar',
        'seis_12_meses_caballar',
        'may_1_año_caballar',
        'total_caballar',
        'men_6_mese_mular',
        'seis_12_meses_mular',
        'may_1_año_mular',
        'total_mular',
        'men_6_mese_asnal',
        'seis_12_meses_asnal',
        'may_1_año_asnal',
        'total_asnal',
        'total_equidos',
    ];

    public function predio()
    {
        return $this->belongsTo(Predios::class, 'id_predio');
    }
}
