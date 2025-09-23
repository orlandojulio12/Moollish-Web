<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Predios extends Model
{

    protected $fillable = ['id_propietario', 'created_by', 'cod_predio', 'nombre_predio', 'departamento', 'municipio', 'vereda', 'forma_de_llegar', 'latitud', 'longitud'];
    protected $table = 'predios';
// app/Models/Predios.php

public function createdBy()
{
    return $this->belongsTo(User::class, 'created_by');
}


public function parametroDiasGestacion()
{
    return $this->hasOne(ParametroDiasGestacion::class, 'predio_id');
}



public function parametros()
{
    return $this->hasMany(\App\Models\PredioParametro::class, 'predio_id');
}

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'predios_x_usuario', 'id_predio', 'id_usuario');
    }

    public function propietario()
    {
        return $this->belongsTo(User::class, 'id_user', 'id'); // 'id_user' es la clave foránea en la tabla predios que apunta al ID del usuario
    }

    public function lotes()
    {
        return $this->hasMany(Lote::class, 'predio_id');
    }

    public function potreros()
    {
        return $this->hasMany(Potrero::class, 'predio_id');
    }

    public function animales()
    {
        return $this->hasMany(Animal::class, 'id_predio');
    }

    public function info_tierra_agua()
    {
        return $this->hasMany(\App\Models\InfoTierraAgua::class, 'id', 'id_predio');
    }

    public function censoBovinos()
    {
        return $this->hasMany(CensoBovino::class, 'id_predio');
    }
    public function asignaciones()
    {
        return $this->hasMany(AsignPrediosEncuestador::class, 'id_predio');
    }

}
