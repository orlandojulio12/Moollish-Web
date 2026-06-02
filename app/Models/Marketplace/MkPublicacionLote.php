<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class MkPublicacionLote extends Model
{
    protected $table = 'mk_publicaciones_lote';

    protected $fillable = [
        'user_id', 'nombre', 'descripcion', 'precio_tipo', 'precio',
        'proposito', 'punto_entrega', 'responsable_flete',
        'guia_movilizacion', 'tiempo_retiro', 'acepta_visita',
        'condicion_pago', 'video_youtube', 'estado', 'destacado',
    ];

    protected $casts = [
        'acepta_visita' => 'boolean',
        'destacado'     => 'boolean',
        'precio'        => 'decimal:2',
    ];

    public function vendedor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function animales()
    {
        return $this->hasMany(MkPublicacionLoteAnimal::class, 'publicacion_lote_id');
    }

    public function fotos()
    {
        return $this->hasMany(MkPublicacionLoteFoto::class, 'publicacion_lote_id');
    }

    public function certificados()
    {
        return $this->hasMany(MkPublicacionCertificado::class, 'publicacion_id')
                    ->where('tipo_publicacion', 'lote');
    }

    public function getNumAnimalesAttribute()
    {
        return $this->animales()->count();
    }

    public function getPrecioPorCabezaAttribute()
    {
        $num = $this->num_animales;
        if ($num === 0) return 0;
        return $this->precio_tipo === 'total'
            ? $this->precio / $num
            : $this->precio;
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
