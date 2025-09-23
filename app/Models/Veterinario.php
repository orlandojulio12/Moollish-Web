<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Veterinario extends Model
{
    use HasFactory;

    protected $table = 'veterinarios';
    protected $primaryKey = 'id';

    protected $fillable = [
        'predio_id',
        'nombre_completo',
        'numero_documento',
        'celular',
        'correo_electronico',
        'sexo',
    ];

    public function palpaciones()
    {
        return $this->hasMany(Palpacion::class, 'id_palpador');
    }
}
