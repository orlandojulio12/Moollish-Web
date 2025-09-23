<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CensoOvinoCaprino extends Model
{
    use HasFactory;

    protected $table = 'censo_ovino_caprino';

    protected $fillable = [
        'id_predio',
        'men_6_meses_h_ovi',
        'may_6_meses_h_ovi',
        'total_hembras_ovinas',
        'men_6_meses_m_ovi',
        'may_6_meses_m_ovi',
        'total_machos_ovi',
        'total_ovinos',
        'men_6_meses_h_capri',
        'may_6_meses_h_capri',
        'total_hembras_capri',
        'men_6_meses_m_capri',
        'may_6_meses_m_capri',
        'total_machos_capri',
        'total_caprinos',
    ];

    public function predio()
    {
        return $this->belongsTo(Predios::class, 'id_predio');
    }
}
