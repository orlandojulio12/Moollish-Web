<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MuerteAnimal extends Model
{
    use HasFactory;

    protected $table = 'registro_muertes';

    protected $fillable = [
        'id_animal',
        'fecha_muerte',
        'observaciones',
    ];

    /**
     * Relación con el modelo Animal.
     */
    public function animal()
    {
        return $this->belongsTo(Animal::class, 'id_animal', 'id_animal');
    }
}
