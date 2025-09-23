<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CensoAvesComerciales extends Model
{
    use HasFactory;

    protected $table = 'censo_aves_comerciales';

    protected $fillable = [
        'id_predio',
        'id_tipo_ave_comercial',
        'linea',
        'num_aves',
        'edad',
        'num_galones',
        'area_galones',
        'densidad',
        'tiemp_descan_lotes',
        'procedencia_aves',
    ];

    // Relación con el modelo Predios
    public function predio()
    {
        return $this->belongsTo(Predios::class, 'id_predio');
    }

    // Relación con el modelo TipoAveComercial
    public function tipoAveComercial()
    {
        return $this->belongsTo(TipoAveComercial::class, 'id_tipo_ave_comercial');
    }
}

