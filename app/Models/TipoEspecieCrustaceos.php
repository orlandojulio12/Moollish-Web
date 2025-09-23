<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEspecieCrustaceos extends Model
{
    use HasFactory;

    protected $table = 'tipo_especie_cructaceos';

    protected $fillable = [
        'nombre',
    ];

    // Relación hasMany: Un tipo de especie de crustáceo puede tener muchos censos
    public function censosCrustaceos()
    {
        return $this->hasMany(CensoCructaceo::class, 'id_tipo_esp_cructac');
    }
}

