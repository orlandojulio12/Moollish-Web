<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEspeciePeces extends Model
{
    use HasFactory;

    protected $table = 'tipo_especie_peces';

    protected $fillable = [
        'nombre',
    ];
}
