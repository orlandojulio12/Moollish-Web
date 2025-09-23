<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CensoOtrasEspec extends Model
{
    use HasFactory;

    protected $table = 'censo_otras_espec';

    protected $fillable = [
        'id_predio',
        'llamas',
        'alpacas',
        'avectruces',
        'otras',
        'cuantas_otras',
    ];

    public function predio()
    {
        return $this->belongsTo(Predios::class, 'id_predio');
    }
}
