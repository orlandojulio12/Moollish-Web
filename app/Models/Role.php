<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

    class Role extends Model
    {
        
        protected $perPage = 20;

        protected $fillable = ['name'];


        public function users()
        {
            return $this->hasMany(\App\Models\User::class, 'id', 'id_rol');
        }
        
    }
