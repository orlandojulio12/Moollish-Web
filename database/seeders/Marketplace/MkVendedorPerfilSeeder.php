<?php

namespace Database\Seeders\Marketplace;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MkVendedorPerfilSeeder extends Seeder
{
    public function run()
    {
        // Toma los primeros 3 usuarios existentes en la BD
        $users = DB::table('users')->take(3)->get();

        $perfiles = [
            [
                'nombre_finca' => 'Hacienda El Paraíso',
                'descripcion'  => 'Ganadería de cría y levante con más de 20 años de experiencia en razas Brahman y Cebú.',
                'departamento' => 'Córdoba',
                'municipio'    => 'Montería',
                'whatsapp'     => '3001234567',
                'verificado'   => true,
                'activo'       => true,
            ],
            [
                'nombre_finca' => 'Hacienda Los Andes',
                'descripcion'  => 'Especialistas en ganado de leche Holstein y doble propósito.',
                'departamento' => 'Antioquia',
                'municipio'    => 'Caucasia',
                'whatsapp'     => '3109876543',
                'verificado'   => true,
                'activo'       => true,
            ],
            [
                'nombre_finca' => 'Agropecuaria Los Llanos',
                'descripcion'  => 'Proveedor de insumos ganaderos y animales de engorde del Meta.',
                'departamento' => 'Meta',
                'municipio'    => 'Villavicencio',
                'whatsapp'     => '3205551234',
                'verificado'   => false,
                'activo'       => true,
            ],
        ];

        foreach ($users as $index => $user) {
            if (!isset($perfiles[$index])) break;

            // Solo insertar si no existe ya
            $existe = DB::table('mk_vendedor_perfil')
                ->where('user_id', $user->id)
                ->exists();

            if (!$existe) {
                DB::table('mk_vendedor_perfil')->insert(array_merge(
                    $perfiles[$index],
                    [
                        'user_id'    => $user->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ));
            }
        }
    }
}
