<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Propietario
 *
 * @property $id
 * @property $nombre_completo
 * @property $tipo_doc
 * @property $num_doc
 * @property $genero
 * @property $correo_electronico
 * @property $telefono
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Propietario extends Model
{
    
    protected $perPage = 20;

    protected $fillable = ['nombre_completo', 'tipo_doc', 'num_doc', 'genero', 'correo_electronico', 'telefono', 'id_user'];

    public function predios()
    {
        return $this->hasMany(Predios::class, 'id_propietario');
    }
}
