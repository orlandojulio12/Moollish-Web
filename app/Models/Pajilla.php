<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pajilla extends Model
{
    //
    use HasFactory;
    protected $table = 'pajillas_semen';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_predio',
        'codigo_pajilla',
        'nombre_reproductor',
        'raza',
        'vendedor',
        'costo_unidad',
        'fecha_entrada',
        'cantidad',
        'valor_total',

    ];

}
