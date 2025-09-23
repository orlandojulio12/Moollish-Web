<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoAbejas extends Model
{
    use HasFactory;
    protected $table = 'tipo_abejas';

    protected $fillable = [
        'nombre',
    ];
}
