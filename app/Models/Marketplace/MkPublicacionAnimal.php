<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class MkPublicacionAnimal extends Model
{
    protected $table = 'mk_publicaciones_animal';

    protected $fillable = [
        'animal_id', 'user_id', 'precio_venta', 'proposito',
        'categoria_animal', 'condicion_corporal', 'temperamento',
        'castrado', 'marcas_senales', 'procedencia',
        'estado_sanitario', 'video_youtube', 'estado', 'destacado',
    ];

    protected $casts = [
        'castrado'     => 'boolean',
        'destacado'    => 'boolean',
        'precio_venta' => 'decimal:2',
    ];

    public function vendedor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function animal()
    {
        return $this->belongsTo(\App\Models\Animal::class, 'animal_id', 'id_animal');
    }

    public function fotos()
    {
        return $this->hasMany(MkPublicacionAnimalFoto::class, 'publicacion_id');
    }

    public function certificados()
    {
        return $this->hasMany(MkPublicacionCertificado::class, 'publicacion_id')
                    ->where('tipo_publicacion', 'animal');
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
