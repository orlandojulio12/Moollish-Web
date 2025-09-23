<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TpServicioAmbientales extends Model
{
    use HasFactory;
    protected $table = 'tp_servicio_ambient';

    protected $fillable = [
        'nombre',
    ];
}
