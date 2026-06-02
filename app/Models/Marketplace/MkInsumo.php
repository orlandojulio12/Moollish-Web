<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class MkInsumo extends Model
{
    protected $table = 'mk_insumos';

    protected $fillable = [
        'user_id', 'nombre', 'marca', 'descripcion', 'categoria',
        'precio', 'unidad', 'stock', 'registro_ica', 'composicion',
        'uso_recomendado', 'dosis', 'animales_objetivo',
        'estado', 'destacado',
    ];

    protected $casts = [
        'destacado' => 'boolean',
        'precio'    => 'decimal:2',
        'stock'     => 'integer',
    ];

    public function vendedor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function fotos()
    {
        return $this->hasMany(MkInsumoFoto::class, 'insumo_id');
    }

    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    public function scopeDestacados($query)
    {
        return $query->where('destacado', true);
    }
}
