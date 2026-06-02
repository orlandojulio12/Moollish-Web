<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Model;

class MkPublicacionLoteFoto extends Model
{
    protected $table = 'mk_publicacion_lote_fotos';

    protected $fillable = ['publicacion_lote_id', 'url_foto', 'orden'];

    public function publicacionLote()
    {
        return $this->belongsTo(MkPublicacionLote::class, 'publicacion_lote_id');
    }
}
