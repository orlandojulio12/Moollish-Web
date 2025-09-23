<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AnimalEstadoProductivo extends Model
{
    use HasFactory;

    protected $table = 'animal_estado_productivo';
    
    protected $fillable = [
        'id_animal',
        'id_estado_productivo',
        'fecha_inicio',
        'fecha_fin'
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class, 'id_animal');
    }

    public function estadoProductivo()
    {
        return $this->belongsTo(EstadoProductivo::class, 'id_estado_productivo');
    }

    protected $dates = ['fecha_inicio', 'fecha_fin'];
}

