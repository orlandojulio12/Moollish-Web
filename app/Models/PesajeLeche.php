<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesajeLeche extends Model
{
    use HasFactory;

    protected $table = 'pesaje_leche_animal';

    protected $primaryKey = 'id_pesaje_leche';

    protected $fillable = [
        'id_animal',
        'dias_parida',
        'fecha_pesaje', // Asegúrate de que este campo está incluido
        'pesaje_am',
        'pesaje_pm',
        'total_pesaje',
        'created_by',
    ];
    protected $dates = ['fecha_pesaje'];

    // Relaciones

    public function animal()
    {
        return $this->belongsTo(Animal::class, 'id_animal');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}