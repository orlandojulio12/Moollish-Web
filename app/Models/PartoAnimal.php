<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartoAnimal extends Model
{
    use HasFactory;

    protected $table = 'parto_animal';
    protected $primaryKey = 'id_parto';
    protected $fillable = ['id_animal', 'id_cria', 'tipo_parto', 'observaciones', 'fecha_parto', 'padre', 'padre_nombre'];
    protected $dates = ['fecha_parto'];

    /**
     * Relación con la madre.
     */
    public function madre()
    {
        return $this->belongsTo(Animal::class, 'id_animal');
    }

    /**
     * Relación con la cría principal.
     */
    public function criaPrincipal()
    {
        return $this->belongsTo(Animal::class, 'id_cria');
    }

    /**
     * Relación muchos a muchos con las crías via tabla pivote.
     */
    public function criasViaPivot()
    {
        return $this->belongsToMany(
            Animal::class,      // Modelo final (cría)
            'parto_cria',       // Tabla pivote
            'id_parto',         // Foreign key en la tabla pivote que referencia a PartoAnimal
            'id_cria'           // Foreign key en la tabla pivote que referencia a Animal (cría)
        )
            ->withPivot('id_parto')      // Incluye campos adicionales de la tabla pivote si es necesario
            ->withTimestamps();          // Incluye marcas de tiempo si tu tabla pivote las tiene
    }
}
