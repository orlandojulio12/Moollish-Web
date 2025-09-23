<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoAveComercial extends Model
{
    use HasFactory;
    protected $table = 'tipo_ave_comcercial';

    protected $fillable = [
        'nombre',
    ];
}
