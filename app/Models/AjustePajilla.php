<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class AjustePajilla extends Model
{
        use HasFactory;

        protected $table = 'ajustes_pajillas';
        protected $primaryKey = 'id';

        protected $fillable = [
            'fecha',
            'id_pajilla',
            'cantidad',
            'motivo',
            'observacion',

        ];
}
