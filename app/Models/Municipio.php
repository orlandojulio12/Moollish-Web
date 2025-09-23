<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    use HasFactory;
    protected $table = 'municipio';
    protected $fillable = ['municipio', 'departamento'];
    protected $primaryKey = 'id';
    public function Predios()
    {
        return $this->hasMany(\App\Models\Predios::class, 'id', 'id_departamento');
    }
}
