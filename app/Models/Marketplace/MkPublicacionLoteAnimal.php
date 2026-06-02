<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Model;

class MkPublicacionLoteAnimal extends Model
{
    protected $table = 'mk_publicacion_lote_animales';

    protected $fillable = ['publicacion_lote_id', 'animal_id'];

    public function publicacionLote()
    {
        return $this->belongsTo(MkPublicacionLote::class, 'publicacion_lote_id');
    }

    public function animal()
    {
        return $this->belongsTo(\App\Models\Animal::class, 'animal_id', 'id_animal');
    }
}
