<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManGenGanado extends Model
{
    use HasFactory;
    protected $table = 'man_gen_ganado';
    protected $fillable = [
        'id_predio',
        'id_raza_gan',
        'ident_animales',
        'sistema_cria_ternero',
        'aliment_ternero',
        'sistem_levant_animal',
        'manej_hembras_prox',
        'manej_vacas_secas',
        'tipo_ordeño',
        'sistem_servic_reproduct',
        'form_program_servicios',
        'pesaje_animal',
        'cuantos_animal_pesa',
        'control_parasito_extern',
        'control_parasito_extern_produc',
        'control_parasito_extern_frecuenc',
        'control_parasito_intern',
        'control_parasito_intern_produc',
        'control_parasito_intern_frecuenc',
        'sumin_sal',
        'a_sal_add_premezcla',
        'a_sal_add_premezcla_especifique',
        'como_manej_ganad_veran',
        'como_manej_ganad_invier',
        'r_pesaje_leche_hembr_lactantes',
        'r_pesaje_leche_hembr_periodicidad',
        'suplement_ganad_epoc_criti',
        'suplement_ganad_epoc_criti_con_que',
        'suplement_ganad_epoc_criti_que_lotes',
    ];
    protected $primaryKey = 'id';
    public function propietarios()
    {
        return $this->belongsTo(Predios::class, 'id_predio');
    }
    public function razas()
    {
        return $this->belongsTo(RazasGanado::class, 'id_raza_gan');
    }
}
