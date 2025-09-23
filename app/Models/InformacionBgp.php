<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class InformacionBgp
 *
 * @property $id
 * @property $id_predio
 * @property $id_tipos_bgp
 * @property $tipo
 * @property $estado
 * @property $created_at
 * @property $updated_at
 *
 * @property Predios $predio
 * @property TiposInformacionBgp $tiposInformacionBgp
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class InformacionBgp extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'informacion_bgp';
    protected $fillable = ['id_predio', 'id_tipos_bgp', 'tipo', 'estado'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function predio()
    {
        return $this->belongsTo(\App\Models\Predios::class, 'id_predio', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tiposInformacionBgp()
    {
        return $this->belongsTo(\App\Models\TiposInformacionBgp::class, 'id_tipos_bgp', 'id');
    }
    
}
