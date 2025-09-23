<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

    class AnimalEstadoReproductivo extends Model
    {
        use HasFactory;

        protected $table = 'animal_estado_reproductivo';
        
        protected $fillable = [
            'id_animal',
            'id_estado_reproductivo',
            'fecha_inicio',
            'fecha_fin'
        ];

        public function animal()
        {
            return $this->belongsTo(Animal::class, 'id_animal');
        }

        public function estadoReproductivo()
        {
            return $this->belongsTo(EstadoReproductivo::class, 'id_estado_reproductivo');
        }

        protected $dates = ['fecha_inicio', 'fecha_fin'];
    }
