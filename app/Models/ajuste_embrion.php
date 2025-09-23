<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ajuste_embrion extends Model
{
    protected $table = 'ajuste_embriones';
    protected $primaryKey = 'id';
    protected $fillable =[
        'fecha',
        'id_embrion',
        'cantidad',
        'motivo',
        'observacion',
    ];
}
