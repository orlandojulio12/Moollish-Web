<?php

namespace Database\Seeders\Marketplace;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MkPublicacionLoteSeeder extends Seeder
{
    public function run()
    {
        $vendedor = DB::table('mk_vendedor_perfil')->first();

        if (!$vendedor) {
            $this->command->warn('No hay vendedores. Ejecuta primero MkVendedorPerfilSeeder.');
            return;
        }

        $lotes = [
            [
                'nombre'            => 'Lote 18 Novillas Holstein',
                'descripcion'       => 'Excelente lote de novillas Holstein de primera calidad, aptas para producción lechera. Todas vacunadas y con documentación al día.',
                'precio_tipo'       => 'total',
                'precio'            => 81000000,
                'proposito'         => 'leche',
                'punto_entrega'     => 'En finca, Montería, Córdoba',
                'responsable_flete' => 'A cargo del comprador',
                'guia_movilizacion' => 'Suministrada por el vendedor',
                'tiempo_retiro'     => 'Máx. 10 días tras el acuerdo',
                'acepta_visita'     => true,
                'condicion_pago'    => 'Se acepta pago total anticipado o 50% al acuerdo y 50% al retiro.',
                'estado'            => 'activo',
                'destacado'         => true,
            ],
            [
                'nombre'            => 'Lote 25 Machos Brahman Engorde',
                'descripcion'       => 'Lote homogéneo de machos Brahman listos para engorde, peso promedio 380kg.',
                'precio_tipo'       => 'por_cabeza',
                'precio'            => 4200000,
                'proposito'         => 'engorde',
                'punto_entrega'     => 'En finca, Caucasia, Antioquia',
                'responsable_flete' => 'A cargo del comprador',
                'guia_movilizacion' => 'Suministrada por el vendedor',
                'tiempo_retiro'     => 'Máx. 15 días tras el acuerdo',
                'acepta_visita'     => true,
                'condicion_pago'    => 'Pago total anticipado. Negociable para clientes frecuentes.',
                'estado'            => 'activo',
                'destacado'         => true,
            ],
            [
                'nombre'            => 'Lote 10 Vacas Doble Propósito',
                'descripcion'       => 'Vacas de doble propósito en producción activa, ideal para pequeños productores.',
                'precio_tipo'       => 'total',
                'precio'            => 38000000,
                'proposito'         => 'mixto',
                'punto_entrega'     => 'En finca, Villavicencio, Meta',
                'responsable_flete' => 'Negociable',
                'guia_movilizacion' => 'Suministrada por el vendedor',
                'tiempo_retiro'     => 'Máx. 7 días tras el acuerdo',
                'acepta_visita'     => true,
                'condicion_pago'    => '50% al acuerdo, 50% al retiro.',
                'estado'            => 'activo',
                'destacado'         => false,
            ],
        ];

        foreach ($lotes as $index => $lote) {
            $loteId = DB::table('mk_publicaciones_lote')->insertGetId(array_merge(
                $lote,
                [
                    'user_id'    => $vendedor->user_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ));

            // Asociar animales reales al lote
            $animales = DB::table('animales')
                ->where('estado_vida', 1)
                ->skip($index * 3)
                ->take(3)
                ->get();

            foreach ($animales as $animal) {
                $existe = DB::table('mk_publicacion_lote_animales')
                    ->where('publicacion_lote_id', $loteId)
                    ->where('animal_id', $animal->id_animal)
                    ->exists();

                if (!$existe) {
                    DB::table('mk_publicacion_lote_animales')->insert([
                        'publicacion_lote_id' => $loteId,
                        'animal_id'           => $animal->id_animal,
                        'created_at'          => now(),
                        'updated_at'          => now(),
                    ]);
                }
            }

            // 2 fotos por lote
            DB::table('mk_publicacion_lote_fotos')->insert([
                [
                    'publicacion_lote_id' => $loteId,
                    'url_foto'            => 'https://placehold.co/600x500/2d5a27/white?text=Lote+' . ($index + 1),
                    'orden'               => 1,
                    'created_at'          => now(),
                    'updated_at'          => now(),
                ],
                [
                    'publicacion_lote_id' => $loteId,
                    'url_foto'            => 'https://placehold.co/600x500/1a3d17/white?text=Foto+Grupal',
                    'orden'               => 2,
                    'created_at'          => now(),
                    'updated_at'          => now(),
                ],
            ]);
        }
    }
}
