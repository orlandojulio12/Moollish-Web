<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

    class EstadoReproductivo extends Model
    {
        use HasFactory;

        protected $table = 'estado_reproductivo';
        protected $primaryKey = 'id';

        protected $fillable = [
            'tipo_animal', // macho o hembra
            'nombre',      // Nombre del estado reproductivo (ej. "Macho en reposo", "Hembra en celo")
            'descripcion'  // Descripción detallada del estado
        ];
/* Hembras */
        const PRENADA = 1;
        const VACIA = 2;

    /* Ambos */
        const DESCONOCIDO = 3;
        // Relación con animales
        public function animales()
        {
            return $this->hasMany(Animal::class, 'estado_reproductivo_id');
        }

        public function animal()
        {
            return $this->belongsTo(Animal::class, 'id_animal');
        }
    }
