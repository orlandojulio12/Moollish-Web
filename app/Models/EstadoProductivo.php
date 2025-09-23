<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoProductivo extends Model
{
    use HasFactory;

    protected $table = 'estado_productivo'; 
    protected $primaryKey = 'id';

    protected $fillable = [
        'nombre',      // Nombre del estado productivo (ej. "En lactancia", "Vaca seca")
        'descripcion'  // Descripción detallada del estado productivo
    ];

    /* HEMBRAS */
    const VACA_PARIDA = 1;
    const VACA_SECA = 2;
    const NOVILLA_VIENTRE = 3;
    const HEMBRA_LEVANTE = 4;
    const CRIA_HEMBRA = 7;
    /* MACHOS */
    const REPRODUCTOR_TORO = 12;
    const MACHO_CEBA = 13;
    const MACHO_LEVANTE = 14;
    const CRIA_MACHO = 15;

    public function animal()
    {
        return $this->belongsTo(Animal::class, 'id_animal'); // Relación con el modelo Animal
    }

    public function animales()
    {
        return $this->hasMany(Animal::class, 'estado_productivo_id');
    }
}
