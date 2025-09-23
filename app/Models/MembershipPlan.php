<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MembershipPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'min_animales',
        'max_animales',
        'duracion_dias',
        'free_trial_dias',
        'precio',
    ];

    public function userMemberships()
    {
        return $this->hasMany(UserMembership::class);
    }

    /**
     * Determina si el plan de membresía es de prueba gratuita
     *
     * @return bool
     */
    public function getIsFreeTrialAttribute()
    {
        // Usar el campo free_trial_dias que existe en este modelo
        return $this->free_trial_dias > 0;
    }
}

