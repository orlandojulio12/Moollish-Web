<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignPrediosEncuestador extends Model
{
    use HasFactory;

    protected $table = 'asign_predios_encuestador';
    protected $fillable = [
        'id_encuestador',
        'id_predio',
    ];

    // Relación con el modelo User (Encuestador)
    public function encuestador()
    {
        return $this->belongsTo(User::class, 'id_encuestador');
    }

    // Relación con el modelo Predio
    public function predio()
    {
        return $this->belongsTo(Predios::class, 'id_predio');
    }

}
