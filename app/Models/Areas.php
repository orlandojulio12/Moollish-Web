<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Areas extends Model
{
    use HasFactory;

    protected $table = 'areas';
    protected $primaryKey = 'id';

    protected $fillable = [
        'id_tipo_area',
        'id_predio',
        'medidas',
        'materiales_establecidos',
        'cant_total',
        'imagen',
        'tipo_medidas',
        'created_by', // Asegúrate de incluir este campo
    ];

    public function predio()
    {
        return $this->belongsTo(Predios::class, 'id_predio');
    }

    public function tiposAreas()
    {
        return $this->belongsTo(TiposAreas::class, 'id_tipo_area');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
