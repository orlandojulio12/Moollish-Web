<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InseminacionArtificial extends Model
{
    use HasFactory;

    protected $table = 'inseminacion';
    protected $primaryKey = 'id';

    protected $fillable = [
        'fecha_inseminacion',
        'id_vaca',
        'id_pajilla'
    ];
}
