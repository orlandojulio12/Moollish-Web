<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CensoPez extends Model
{
    use HasFactory;

    protected $table = 'censo_peces';

    protected $fillable = [
        'id_predio',
        'id_tipo_esp_peces',
        'ovas',
        'alevinos',
        'engorde',
        'reproductores',
        'total_pez_especie',
        'total_peces',
    ];

    public function predio()
    {
        return $this->belongsTo(Predios::class, 'id_predio');
    }

    public function tipoEspeciePez()
    {
        return $this->belongsTo(TipoEspeciePeces::class, 'id_tipo_esp_peces');
    }
     
}
