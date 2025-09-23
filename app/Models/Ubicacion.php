<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ubicacion extends Model
{
    use HasFactory;
    protected $table = 'ubicaciones';
    protected $primaryKey = 'id_ubicacion';

    protected $fillable = [
        'potrero',
        'lote',
        'fecha',
    ];


    public function animales()
    {
        return $this->belongsToMany(Animal::class, 'ubicacion_x_animal', 'id_ubicacion', 'id_animal');
    }

}
