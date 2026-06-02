<?php

namespace App\Models\Marketplace;

use Illuminate\Database\Eloquent\Model;

class MkPublicacionCertificado extends Model
{
    protected $table = 'mk_publicacion_certificados';

    protected $fillable = [
        'publicacion_id', 'tipo_publicacion',
        'nombre_certificado', 'url_archivo', 'fecha_emision',
    ];

    protected $casts = [
        'fecha_emision' => 'date',
    ];
}
