<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CensoAbejas extends Model
{
    use HasFactory;

    protected $table = 'censo_abejas';

    protected $fillable = [
        'id_predio',
        'id_tipo_abejas',
        'num_apiarios',
        'num_colmenas',
        'poblacion_estimada',
        'realiz_trashumancia',
        'nom_estable_destino',
        'departamento',
        'municipio',
    ];

    // Relación con el modelo Predios
    public function predio()
    {
        return $this->belongsTo(Predios::class, 'id_predio');
    }

    // Relación con el modelo TipoAbejas
    public function tipoAbejas()
    {
        return $this->belongsTo(TipoAbejas::class, 'id_tipo_abejas');
    }


}
