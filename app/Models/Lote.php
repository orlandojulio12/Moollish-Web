<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    use HasFactory;

    protected $table = 'lotes';
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
        return $this->hasMany(Animal::class, 'lote_id');
    }


}
