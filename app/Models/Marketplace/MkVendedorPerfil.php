<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class MkVendedorPerfil extends Model
{
    protected $table = 'mk_vendedor_perfil';

    protected $fillable = [
        'user_id', 'nombre_finca', 'descripcion',
        'departamento', 'municipio', 'whatsapp',
        'verificado', 'activo',
    ];

    protected $casts = [
        'verificado' => 'boolean',
        'activo'     => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function publicacionesAnimal()
    {
        return $this->hasMany(MkPublicacionAnimal::class, 'user_id', 'user_id');
    }

    public function publicacionesLote()
    {
        return $this->hasMany(MkPublicacionLote::class, 'user_id', 'user_id');
    }

    public function insumos()
    {
        return $this->hasMany(MkInsumo::class, 'user_id', 'user_id');
    }
}
