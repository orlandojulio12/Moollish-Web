<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Predios;

class FichaAnimal extends Model
{
    use HasFactory;

    protected $table = 'ficha_animal'; // Nombre de la tabla en la base de datos
    protected $primaryKey = 'ficha_id'; // Llave primaria
    public $incrementing = true; // Laravel asume que el ID es autoincremental

    protected $fillable = [
        'codigo_animal',
        'id_predio',
        'identificacion_electronica',
        'id_sinigan',
        'fecha_nacimiento',
        'edad',
        'sexo',
        'raza',
        'color',
        'hierro',
        'padre',
        'madre',
        'raza_madre',
        'raza_padre',
        'estado_productivo',
        'estado_reproductivo',
        'fecha_ingreso_hato',
        'ultimo_peso_fecha',
        'ultimo_peso_cantidad',
        'ultimo_servicio'
    ]; // Campos asignables masivamente

    protected $dates = [
        'fecha_nacimiento',
        'fecha_ingreso_hato',
        'ultimo_peso_fecha',
        'created_at',
        'updated_at'
    ]; // Atributos que deben ser tratados como fechas

    // Relación con la tabla 'predios'
    public function predio()
    {
        return $this->belongsTo(Predios::class, 'id_predio');
    }
}
