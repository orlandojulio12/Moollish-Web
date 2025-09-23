<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecadoDestete extends Model
{
    use HasFactory;
    protected $table = 'destetes';
   
    protected $primaryKey = 'id_destete';

    public $timestamps = true;

    protected $fillable = [
        'id_animal',
        'id_cria_animal',
        'is_cria_levante',
        'vaca_secado',
        'peso_cria',
        'peso_vaca',
        'fecha_destete',
        'motivo',
        'observacion',
    ];

    protected $casts = [
        'is_cria_levante' => 'boolean',
        'vaca_secado' => 'boolean',
        'fecha_destete' => 'date',
        'peso_cria' => 'float',
        'peso_vaca' => 'float',
    ];

    public function vaca()
    {
        return $this->belongsTo(Animal::class, 'id_animal');
    }

    public function cria()
    {
        return $this->belongsTo(Animal::class, 'id_cria_animal');
    }

    public function getCategoriaPostDesteteAttribute()
    {
        $animal = $this->cria;

        if (!$animal) {
            return 'Categoría desconocida'; // Manejo de casos donde no exista la cría asociada
        }

        if ($this->is_cria_levante) {
            // Determinar categoría post-destete
            return $animal->sexo === 'macho'
                ? 'Macho de Levante'
                : 'Hembra de Levante';
        }

        // Categorías para crías no destetadas
        return $animal->sexo === 'macho'
            ? 'Macho Cría'
            : 'Hembra Cría';
    }

    public function actualizarEstadosPostDestete()
    {
        $animal = $this->cria;

        if (!$animal) {
            return; // Manejo de errores o salida temprana
        }

        // Actualizar estado productivo
        if ($this->is_cria_levante) {
            $animal->estado_productivo_id = $animal->sexo === 'macho'
                ? EstadoProductivo::MACHO_LEVANTE
                : EstadoProductivo::HEMBRA_LEVANTE;
        }

        // Actualizar estado reproductivo si es hembra
        if ($animal->sexo === 'hembra' && $animal->estado_reproductivo_id === EstadoReproductivo::DESCONOCIDO) {
            $animal->estado_reproductivo_id = EstadoReproductivo::VACIA;
        }

        $animal->save(); // Guardar los cambios
    }
}
