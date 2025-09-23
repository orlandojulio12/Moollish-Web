<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstalacionesEquipos extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_predio',
        'id_tipos_equipos',
        'si',
        'no',
        'especificar',
    ];
    protected $table = 'instalaciones_equipos';
    protected $primaryKey = 'id';
    public function Predios()
    {
        return $this->belongsTo(Predios::class, 'id_predio');
    }
    public function tipos_equipos()
    {
        return $this->belongsTo(TiposInstalacionesEquipo::class, 'id_tipos_equipos');
    }
}
