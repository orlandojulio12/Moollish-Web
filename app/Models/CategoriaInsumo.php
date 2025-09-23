<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriaInsumo extends Model
{
    use SoftDeletes;

    protected $table = 'categorias_insumos';

    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    /**
     * Relación con los insumos asociados a esta categoría
     */
    public function insumos()
    {
        return $this->hasMany(Insumo::class, 'categoria_id');
    }

    /**
     * Relación con los tipos de uso de esta categoría
     */
    public function tiposUso()
    {
        return $this->hasMany(TipoUsoInsumo::class, 'categoria_insumo_id');
    }
}
