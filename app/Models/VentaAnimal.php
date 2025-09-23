<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaAnimal extends Model
{
    use HasFactory;

    protected $table = 'registro_ventas';
    protected $primaryKey = 'id_venta';
    public $timestamps = true;

    protected $fillable = [
        'id_animal',
        'fecha_venta',
        'precio',
        'comprador',
        'observaciones',
    ];

    // Relación con el modelo Animal
    public function animal()
    {
        return $this->belongsTo(Animal::class, 'id_animal', 'id_animal');
    }
}
