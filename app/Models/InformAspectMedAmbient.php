<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InformAspectMedAmbient extends Model
{
    use HasFactory;
    protected $table = 'inform_aspect_med_ambient';
    protected $fillable = [
        'id_predio', 
        'dispos_aguas_servid', 
        'dispos_excrement_bovinos', 
        'manejo_basuras', 
        'manejo_empaq_produc_quimic'
    ];
    protected $primaryKey = 'id';
    public function Predios()
    {
        return $this->belongsTo(Predios::class, 'id_predio');
    }
}
