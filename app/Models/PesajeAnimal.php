<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesajeAnimal extends Model
{
    use HasFactory;

    protected $table = 'pesaje_animal';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_animal',
        'peso',
        'fecha_pesaje',
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class, 'id_animal');
    }
    


}


   


 
