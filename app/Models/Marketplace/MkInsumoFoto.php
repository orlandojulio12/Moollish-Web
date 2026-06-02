<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Model;

class MkInsumoFoto extends Model
{
    protected $table = 'mk_insumos_fotos';

    protected $fillable = ['insumo_id', 'url_foto', 'orden'];

    public function insumo()
    {
        return $this->belongsTo(MkInsumo::class, 'insumo_id');
    }
}
