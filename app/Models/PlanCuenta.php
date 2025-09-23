<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanCuenta extends Model
{
    use HasFactory;

    protected $table = 'plan_cuentas';

    protected $fillable = [
        'codcta',
        'nomcta',
        'nivel',
        'grupo',
        'naturaleza',
        'tipocta',
        'catfinan',
        'ctacierra',
        'pagoter',
        'actfijo',
        'cencostos',
    ];
    
}

