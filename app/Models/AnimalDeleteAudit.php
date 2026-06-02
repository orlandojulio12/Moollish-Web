<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnimalDeleteAudit extends Model
{
    protected $table = 'animal_delete_audits';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'animal_id',
        'predio_id',
        'action',
        'status',
        'ip_address',
        'user_agent',
        'animal_snapshot'
    ];
    
        protected $casts = [
        'animal_snapshot' => 'array',
    ];
}