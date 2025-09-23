<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MontaNatural extends Model
{
    protected $table = 'monta_natural'; // Nombre de la tabla
    protected $primaryKey = 'id'; // Clave primaria

    protected $fillable = [
        'id_vaca',
        'id_toro',
        'fecha_monta',
    ];


    public function vaca()
    {
        return $this->belongsTo(Animal::class, 'id_vaca');
    }

    public function toro()
    {
        return $this->belongsTo(Animal::class, 'id_toro');
    }
}
