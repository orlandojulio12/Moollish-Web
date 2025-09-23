<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMembership extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'membership_plan_id',
        'fecha_inicio',
        'fecha_expiracion',
        'estado',
        'es_free_trial',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function membershipPlan()
    {
        return $this->belongsTo(MembershipPlan::class);
    }

    public function isActive()
    {
        return $this->estado === 'activo' && $this->fecha_expiracion >= now()->toDateString();
    }

    public function isFalse()
    {
        return $this->fecha_expiracion < now()->toDateString();
    }

    public function getIsActiveAttribute()
    {
        return $this->isActive();
    }

    /**
     * Determina si la membresía del usuario es de prueba gratuita
     *
     * @return bool
     */
    public function getIsFreeTrialAttribute()
    {
        // Primero verificamos si esta membresía específica está marcada como prueba gratuita
        if ($this->es_free_trial) {
            return true;
        }

        // Si no, verificamos si el plan asociado es de prueba gratuita
        return $this->membershipPlan->isFreeTrial;
    }
}
