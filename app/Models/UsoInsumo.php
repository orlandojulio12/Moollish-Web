<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsoInsumo extends Model
{
    protected $table = 'usos_insumos';

    protected $fillable = [
        'insumo_id',
        'tipo_uso_id',
        'nombre_personalizado',
        'instrucciones',
        'observaciones'
    ];

    public function insumo()
    {
        return $this->belongsTo(Insumo::class);
    }

    public function tipoUso()
    {
        return $this->belongsTo(TipoUsoInsumo::class);
    }
}
