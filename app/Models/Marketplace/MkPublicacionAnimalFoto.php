<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Model;

class MkPublicacionAnimalFoto extends Model
{
    protected $table = 'mk_publicacion_animal_fotos';

    protected $fillable = ['publicacion_id', 'url_foto', 'orden'];

    public function publicacion()
    {
        return $this->belongsTo(MkPublicacionAnimal::class, 'publicacion_id');
    }
}
