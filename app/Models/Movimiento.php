<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    use HasFactory;
    protected $table = 'movimientos_animales';

    protected $fillable = [
        'animal_id', 'predio_id', 'lote_id', 'potrero_id', 'fecha_movimiento', 'motivo'
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class, 'animal_id', 'id_animal'); // Definir clave foránea y primaria
    }

    public function predio()
    {
        return $this->belongsTo(Predios::class);
    }

    public function lote()
    {
        return $this->belongsTo(Lote::class);
    }

    public function potrero()
    {
        return $this->belongsTo(Potrero::class);
    }
}
