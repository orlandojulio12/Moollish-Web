<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class RazasGanado extends Model
{
    
    protected $perPage = 20;
    protected $table = 'razas_ganado';

    protected $fillable = ['nombre_razas', 'created_by'];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
