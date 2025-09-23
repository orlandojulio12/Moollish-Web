<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CensoAvesTraspatio extends Model
{
    use HasFactory;

    protected $table = 'censo_aves_traspatio';

    protected $fillable = [
        'id_predio',
        'id_tipo_ave_transp',
        'num_aves',
        'edad',
        'precedencia_aves',
        'observaciones',
    ];

    // Relación con el modelo Predios
    public function predio()
    {
        return $this->belongsTo(Predios::class, 'id_predio');
    }

    // Relación con el modelo TipoAveTranspatio
    public function tipoAveTranspatio()
    {
        return $this->belongsTo(TipoAveTranspatio::class, 'id_tipo_ave_transp');
    }
}



