<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TiposInstalacionesEquipo
 *
 * @property $id
 * @property $nombre_tipo
 * @property $created_at
 * @property $updated_at
 *
 * @property InstalacionesEquipos[] $instalacionesEquipos
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class TiposInstalacionesEquipo extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nombre_tipo'];
    protected $primaryKey = 'id';

    protected $table = 'tipos_instalaciones_equipos';
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function instalacionesEquipos()
    {
        return $this->hasMany(\App\Models\InstalacionesEquipos::class, 'id', 'id_tipos_equipos');
    }
    
}
