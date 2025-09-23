<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Potrero extends Model
{
    use HasFactory;

    protected $table = 'potreros';
    protected $primaryKey = 'id';

    protected $fillable = [
        'predio_id',
        'nombre',
        'area',
    ];

    public function predio()
    {
        return $this->belongsTo(Predios::class, 'predio_id');
    }


  public function animales()
  {
      return $this->hasMany(Animal::class, 'potrero_id');
  }

}
