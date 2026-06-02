<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'celular',
        'id_rol',
        'estado',
        'profile_photo_path',
        'tipo_documento',
        'documento',
        'created_by',
        'verification_token',
        'regimen'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function memberships()
    {
        return $this->hasMany(UserMembership::class);
    }
    public function membership()
    {
        return $this->hasOne(UserMembership::class)->latestOfMany();
    }
    public function predios()
    {
        return $this->belongsToMany(Predios::class, 'predios_x_usuario', 'id_usuario', 'id_predio');
    }
    public function role()
    {
        return $this->belongsTo(Role::class, 'id_rol');
    }

    public function createdUsers()
    {
        return $this->hasMany(User::class, 'created_by');
    }

    // En App\Models\User.php

    public function animalsCount()
    {
        // Carga los predios con el conteo de animales y suma el campo 'animales_count'
        return $this->predios()->withCount('animales')->get()->sum('animales_count');
    }

    public function canAddAnimal()
    {
        // Si el usuario no tiene una membresía activa, no se le permite agregar
        if (!$this->membership) {
            return false;
        }
        // Obtener el límite máximo de animales según el plan de membresía
        $max = $this->membership->membershipPlan->max_animales;
        // Si no hay límite definido (por ejemplo, null), se permite agregar
        if (is_null($max)) {
            return true;
        }

        // Permite agregar si la suma de animales en todos sus predios es menor que el máximo permitido
        return $this->animalsCount() < $max;
    }
    public function perfilMarketplace()
{
    return $this->hasOne(\App\Models\Marketplace\MkVendedorPerfil::class, 'user_id');
}
}
