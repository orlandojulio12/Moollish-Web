<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TiposAreas
 *
 * @property $id
 * @property $nombre_TiposAreas
 * @property $created_at
 * @property $updated_at
 *
 * @property Predios[] $predios
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class TiposAreas extends Model
{
    
    protected $perPage = 20;
    protected $primaryKey = 'id';
    protected $table = 'tipo_areas';
 
    protected $fillable = ['nombre_areas'];
 

    public function areas()
    {
        return $this->hasMany(\App\Models\Areas::class, 'id', 'id_tipo_area');
    }
    
}
