<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Animal extends Model
{
    use HasFactory;

    protected $table = 'animales';
    protected $primaryKey = 'id_animal';

    protected $fillable = [
        'id_predio',
        'codigo',
        'nombre',
        'identificacion_electronica',
        'id_sinigan',
        'fecha_nacimiento',
        'sexo',
        'raza',
        'color',
        'hierro',
        'fecha_ingreso_hato',
        'madre', //relacion con animal madre
        'raza_madre',
        'madre_nombre',
        'padre', //relacion con animal padre
        'raza_padre',
        'padre_nombre',
        'potrero_id',
        'lote_id',
        'estado_productivo_id',
        'estado_reproductivo_id',
        'estado_vida',
        'es_comprado'
    ];
    protected $dates = ['fecha_parto'];

    const ESTADO_VIVO = 1;
    const ESTADO_MUERTO = 2;
    const ESTADO_VENDIDO = 3;

    const ESTADO_NO_ACTIVO = 4; //ejemplo: donado, perdido, robado, etc.
    

    // En User.php


    public function donacionesEmbriones()
    {
        return $this->hasMany(TransferenciaEmbrion::class, 'donadora_id');
    }

    public function recepcionesEmbriones()
    {
        return $this->hasMany(TransferenciaEmbrion::class, 'receptora_id');
    }
    public function scopeFiltrarPorEstadoYPredio($query, $user, $estadosProductivos = [])
    {
        $query->where('estado_vida', self::ESTADO_VIVO);
        if ($user->role->id !== 1) {
            $query->whereIn('id_predio', $user->predios->pluck('id')->toArray());
        }

        if (!empty($estadosProductivos)) {
            $query->whereIn('estado_productivo_id', $estadosProductivos);
        }
        return $query;
    }


    public function predio()
    {
        return $this->belongsTo(Predios::class, 'id_predio');
    }


    public function medicaciones()
    {
        return $this->hasMany(Medicacion::class, 'id_animal');
    }

    public function ultimoTacto()
    {
        return $this->hasOne(Palpacion::class, 'id_animal')->latest('fecha');
    }

    public function ultimoEstadoReproductivo()
    {
        return $this->hasOne(AnimalEstadoReproductivo::class, 'id_animal')->latest('fecha_inicio');
    }

    public function ultimoEstadoProductivo()
    {
        return $this->hasOne(AnimalEstadoProductivo::class, 'id_animal')->latest('fecha_inicio');
    }

    public function registrosMuerte()
    {
        return $this->hasMany(MuerteAnimal::class, 'id_animal', 'id_animal');
    }

    public function estadosReproductivos()
    {
        return $this->hasMany(AnimalEstadoReproductivo::class, 'id_animal');
    }

public function madreRelacion()
{
    return $this->belongsTo(Animal::class, 'madre', 'id_animal');
}



public function padre()
{
    return $this->belongsTo(Animal::class, 'padre', 'id_animal');
}


    public function criasViaPivot()
    {
        return $this->belongsToMany(
            Animal::class,
            'parto_cria',
            'id_parto',
            'id_cria'
        )
            ->withPivot('id_parto')
            ->withTimestamps();
    }

    public function partosOrdenados()
    {
        return $this->hasMany(PartoAnimal::class, 'id_animal')->orderBy('fecha_parto');
    }

    public function calcularIEP()
    {
        $partos = $this->partosOrdenados()->pluck('fecha_parto');

        if ($partos->count() < 2) {
            return null;
        }

        $ultimoParto = strtotime($partos->last());
        $penultimoParto = strtotime($partos->slice(-2, 1)->first());

        $diferenciaEnDias = ($ultimoParto - $penultimoParto) / 86400;

        return abs($diferenciaEnDias);
    }

    public function getCriasAttribute()
    {
        if (!$this->relationLoaded('partos')) {
            $this->load('partos');
        }

        $crias = collect();

        foreach ($this->partos as $parto) {
            $crias = $crias->merge($parto->criasViaPivot);
        }

        return $crias->unique('id_animal');
    }

    public function partos()
    {
        return $this->hasMany(PartoAnimal::class, 'id_animal');
    }

    public function partosComoCria()
    {
        return $this->belongsToMany(
            PartoAnimal::class,
            'parto_cria',
            'id_cria',
            'id_parto'
        )->withPivot('id_parto')->withTimestamps();
    }

    public function criasPrincipal()
    {
        return $this->hasManyThrough(
            Animal::class,
            PartoAnimal::class,
            'id_animal',
            'id_animal',
            'id_animal',
            'id_cria'
        );
    }
    public function animalEstadoReproductivo()
    {
        return $this->hasMany(AnimalEstadoReproductivo::class, 'id_estado_reproductivo');
    }

    public function lote()
    {
        return $this->belongsTo(Lote::class, 'lote_id');
    }

    public function potrero()
    {
        return $this->belongsTo(Potrero::class, 'potrero_id');
    }

    public function estadosProductivos()
    {
        return $this->hasMany(AnimalEstadoProductivo::class, 'id_animal');
    }

    public function estadoProductivoActual()
    {
        return $this->belongsTo(EstadoProductivo::class, 'estado_productivo_id');
    }

    public function estadoReproductivoActual()
    {
        return $this->belongsTo(EstadoReproductivo::class, 'estado_reproductivo_id');
    }

    public function pesajes()
    {
        return $this->hasMany(PesajeAnimal::class, 'id_animal');
    }

    public function movimientos()
    {
        return $this->hasMany(Movimiento::class, 'animal_id');
    }

    public function ubicaciones()
    {
        return $this->belongsToMany(Ubicacion::class, 'ubicacion_x_animal', 'id_animal', 'id_ubicacion');
    }

    public function ultimoPesaje()
    {
        return $this->hasOne(PesajeAnimal::class, 'id_animal')->latestOfMany();
    }

    public function pesajeLeches()
    {
        return $this->hasMany(PesajeLeche::class, 'id_animal');
    }

    public function getTotalLecheAttribute()
    {
        return $this->pesajeLeches()->sum('total_pesaje') ?? 0;
    }

    public function secadoDestetes()
    {
        return $this->hasMany(SecadoDestete::class, 'id_animal');
    }
    public function secadoDestetesComoCria()
    {
        return $this->hasMany(SecadoDestete::class, 'id_cria_animal');
    }

    public function scopeCria($query)
    {
        return $query->whereIn('estado_productivo_id', [
            EstadoProductivo::CRIA_HEMBRA,
            EstadoProductivo::CRIA_MACHO,
        ]);
    }

    public function latestSecadoDesteteComoCria()
    {
        return $this->hasOne(SecadoDestete::class, 'id_cria_animal')->latestOfMany();
    }
    public function getIsCriaLevanteAttribute()
    {
        $latestSecadoDestete = $this->latestSecadoDesteteComoCria;

        if ($latestSecadoDestete) {
            return $latestSecadoDestete->is_cria_levante;
        }

        return false;
    }

    public function estadoProductivo()
    {
        return $this->belongsTo(EstadoProductivo::class, 'estado_productivo_id', 'id');
    }

    public function estadoReproductivo()
    {
        return $this->belongsTo(EstadoReproductivo::class, 'estado_reproductivo_id', 'id');
    }

    public function ultimoParto()
    {
        return $this->hasOne(PartoAnimal::class, 'id_animal')->latest('fecha_parto');
    }

    // Accessors para retornar datos formateados sin sobrescribir la relación original

    // Retorna el nombre del estado productivo a partir de la relación
    public function getEstadoProductivoObjetoAttribute()
    {
        $estado = $this->estadoProductivo()->first();
        return $estado ? $estado->nombre : 'N/A';
    }

    // Retorna el nombre del estado reproductivo a partir de la relación
    public function getEstadoReproductivoObjetoAttribute()
    {
        $estado = $this->estadoReproductivo()->first();
        return $estado ? $estado->nombre : 'N/A';
    }

    // Accessor para la fecha del último parto formateada (Y-m-d)
    public function getUltimoPartoFechaAttribute()
    {
        $ultimo = $this->ultimoParto()->first();
        return $ultimo ? Carbon::parse($ultimo->fecha_parto)->format('Y-m-d') : null;
    }

    // Se agregan al array de atributos convertidos a JSON o array
    protected $appends = [
        'estado_productivo_objeto',
        'estado_reproductivo_objeto',
        'ultimo_parto_fecha'
    ];

}
