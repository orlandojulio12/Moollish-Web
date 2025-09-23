<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class   Palpacion extends Model
{
    use HasFactory;

    protected $table = 'palpaciones';

    protected $primaryKey = 'id';

    protected $fillable = [
        'fecha',
        'id_animal',
        'resultado',
        'parto_proyectado',
        'id_palpador',
        'diagnostico'
    ];

    public function animal()
    {
        return $this->belongsTo(Animal::class, 'id_animal', 'id_animal');
    }

    public function palpador()
    {
        return $this->belongsTo(Veterinario::class, 'id_palpador', 'id');
    }
}
