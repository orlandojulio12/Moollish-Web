<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CensoCructaceo extends Model
{
    use HasFactory;

    protected $table = 'censo_cructaceos';

    protected $fillable = [
        'id_predio',
        'id_tipo_esp_cructac',
        'nauplinos',
        'larvicultura',
        'engorde',
        'reproductores',
        'total_especie_cructacio',
        'total_cructaceos',
    ];

    public function predio()
    {
        return $this->belongsTo(Predios::class, 'id_predio');
    }

    public function tipoEspecieCructaceo()
    {
        return $this->belongsTo(TipoEspecieCrustaceos::class, 'id_tipo_esp_cructac');
    }
}
