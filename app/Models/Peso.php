<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peso extends Model
{
    use HasFactory;

    protected $table = 'pesos';

    protected $fillable = [
        'animal_id',
        'fecha_peso',
        'peso',
        'metodo_pesaje',
        'observaciones'
    ];

    protected $casts = [
        'fecha_peso' => 'date',
        'peso' => 'float'
    ];

    /**
     * Obtiene el animal al que pertenece este peso
     */
    public function animal()
    {
        return $this->belongsTo(Animal::class);
    }
}
