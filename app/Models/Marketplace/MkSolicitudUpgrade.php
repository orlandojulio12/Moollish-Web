<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class MkSolicitudUpgrade extends Model
{
    protected $table = 'mk_solicitudes_upgrade';

    protected $fillable = [
        'user_id', 'rol_actual', 'rol_solicitado',
        'estado', 'mensaje', 'respondido_por', 'respondido_at',
    ];

    protected $casts = [
        'respondido_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function respondidoPor()
    {
        return $this->belongsTo(User::class, 'respondido_por');
    }

    public function getNombreRolSolicitadoAttribute(): string
    {
        return match($this->rol_solicitado) {
            6 => 'Proveedor',
            3 => 'Propietario Moollish',
            default => 'Desconocido',
        };
    }
}
