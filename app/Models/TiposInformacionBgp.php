<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TiposInformacionBgp extends Model
{
    use HasFactory;
    protected $table = 'tipos_informacion_bgp';
    protected $primaryKey = 'id';
    public function informacion_bpg()
    {
        return $this->hasMany(\App\Models\InformacionBgp::class, 'id', 'id_tipos_bgp');
    }
}
