<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CensoPorcino extends Model
{
    use HasFactory;

    protected $table = 'censo_porcinos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_predio',
        'lact_hast_30_dias',
        'precebo_31_a_60_dias',
        'lev_ceb_61_180_dias',
        'reempl_men_8_meses_h',
        'cria_men_8_meses_h',
        'macho_reprod_men_6_meses',
        'total_porcinos',
    ];

    public function predio()
    {
        return $this->belongsTo(Predios::class, 'id_predio');
    }
}
