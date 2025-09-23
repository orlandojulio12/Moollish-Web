<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferenciaEmbrion extends Model
{
    use HasFactory;

    protected $table = 'transferencia_embriones';

    protected $fillable = [
        'predio_id',
        'id_vaca', //receptora
        'id_embrion',
        'fecha_transferencia',
        'observaciones'
    ];

    // Relaciones
    public function predio()
    {
        return $this->belongsTo(Predios::class, 'predio_id');
    }

    public function receptora()
    {
        return $this->belongsTo(Animal::class, 'id_vaca', 'id_animal');
    }
}
