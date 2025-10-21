<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimientos extends Model
{
    use HasFactory;

    protected $table = 'movimientos';

    protected $fillable = [
        'usuario_id',
        'id_predio',
        'cantidad',
        'fecha',
        'descripcion',
        'plan_cuenta'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function predio()
    {
        return $this->belongsTo(Predios::class, 'id_predio');
    }

    public function planCuenta()
    {
        return $this->belongsTo(PlanCuenta::class, 'plan_cuenta', 'codcta');
    }
    
    public function subcuentas()
    {
        return $this->hasMany(PlanCuenta::class, 'codcta', 'codcta')
            ->whereRaw('LENGTH(codcta) = 8');
    }
}
