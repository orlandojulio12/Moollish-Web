<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroCompra extends Model
{
    use HasFactory;

    protected $table = 'registro_compras';

    protected $fillable = [
        'id_animal',
        'proveedor',
        'fecha_compra',
        'precio_compra',
    ];

    public $timestamps = false;
}
