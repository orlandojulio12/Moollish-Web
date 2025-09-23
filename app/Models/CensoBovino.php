<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CensoBovino extends Model
{
    use HasFactory;

    protected $table = 'censo_bovinos';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id_predio',
        'men_3_meses_h',
        'tres_a_9_meses_h',
        'nueve_a_12_meses_h',
        'uno_a_2_años_h',
        'dos_a_3_años_h',
        'tres_a_5_años_h',
        'may_5_años_h',
        'total_hembras',
        'men_3_meses_m',
        'tres_a_9_meses_m',
        'nueve_a_12_meses_m',
        'uno_a_2_años_m',
        'dos_a_3_años_m',
        'may_3_años',
        'total_machos',
        'total_bovinos',
    ];

    public function predio()
    {
        return $this->belongsTo(Predios::class, 'id_predio');
    }
}
