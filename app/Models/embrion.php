<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class embrion extends Model
{
    //
    protected $table =      'embriones';
    protected $primaryKey = 'id';
    protected $fillable = [
                            'id_predio',
                            'codigo_embrion',
                            'nombre_reproductor',
                            'raza_reproductor',
                            'vaca_donadora',
                            'raza_vaca',
                            'vendedor',
                            'costo_unidad',
                            'fecha_entrada',
                            'cantidad',
                            'valor_total'
    ];

    public function predio()
    {
        return $this->belongsTo(Predios::class, 'id_predio');
    }
}
