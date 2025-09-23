<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoUsoInsumo extends Model
{
    use SoftDeletes;

    protected $table = 'tipos_usos_insumos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'categoria_insumo_id'
    ];

    /**
     * Relación con la categoría de insumo
     */
    public function categoria()
    {
        return $this->belongsTo(CategoriaInsumo::class, 'categoria_insumo_id');
    }

    /**
     * Relación con los usos de insumos
     */
    public function usos()
    {
        return $this->hasMany(UsoInsumo::class, 'tipo_uso_id');
    }

    /**
     * Relación con las aplicaciones
     */
    public function aplicaciones()
    {
        return $this->hasMany(AplicacionInsumo::class, 'tipo_uso_id');
    }
}