<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;
    protected $table = 'inventarios';
    protected $primaryKey = 'id_inventario';

    protected $fillable = [
        'id_predio',
        'fecha_inicio',
        'fecha_fin',
        'nombre_inventario',
        'estado',
        'observaciones',
        'creado_por',
        'animales',
        'animales_faltantes',
    ];

    protected $casts = [
        'animales' => 'array', // Convertir automáticamente el campo JSON a un array PHP
        'animales_faltantes' => 'array', // Convertir automáticamente el campo JSON a un array PHP
    ];

    public function predio()
    {
        return $this->belongsTo(Predios::class, 'id_predio', 'id');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'creado_por', 'id');
    }

}
