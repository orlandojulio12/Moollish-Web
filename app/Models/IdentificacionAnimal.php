<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdentificacionAnimal extends Model
{
    use HasFactory;

    protected $table = 'identificacion_animal';

    protected $fillable = [
        'id_predio',
        'porcinos_con',
        'porcinos_sin',
        'total_porcinos',
        'bovinos_con',
        'bovinos_sin',
        'total_bovinos',
        'bufalinos_con',
        'bufalinos_sin',
        'total_bufalinos',
    ];

    public function predio()
    {
        return $this->belongsTo(Predios::class, 'id_predio');
    }
}

