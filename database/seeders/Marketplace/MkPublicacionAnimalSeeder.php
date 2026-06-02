<?php

namespace Database\Seeders\Marketplace;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MkPublicacionAnimalSeeder extends Seeder
{
    public function run()
    {
        // Toma animales reales de la BD (solo los vivos estado_vida = 1)
        $animales = DB::table('animales')
            ->where('estado_vida', 1)
            ->take(6)
            ->get();

        // Toma el primer user con perfil de vendedor
        $vendedor = DB::table('mk_vendedor_perfil')->first();

        if (!$vendedor || $animales->isEmpty()) {
            $this->command->warn('No hay animales o vendedores. Ejecuta primero MkVendedorPerfilSeeder.');
            return;
        }

        $propositos    = ['engorde', 'leche', 'reproduccion', 'sacrificio'];
        $categorias    = ['Toro adulto', 'Novilla', 'Vaca de vientre', 'Ternero', 'Torete'];
        $temperamentos = ['Manso', 'Nervioso', 'Bravo'];
        $procedencias  = ['Ganadería propia', 'Comprado', 'Subasta'];

        foreach ($animales as $index => $animal) {
            $existe = DB::table('mk_publicaciones_animal')
                ->where('animal_id', $animal->id_animal)
                ->exists();

            if ($existe) continue;

            $publicacionId = DB::table('mk_publicaciones_animal')->insertGetId([
                'animal_id'          => $animal->id_animal,
                'user_id'            => $vendedor->user_id,
                'precio_venta'       => rand(2, 8) * 1000000,
                'proposito'          => $propositos[array_rand($propositos)],
                'categoria_animal'   => $categorias[array_rand($categorias)],
                'condicion_corporal' => rand(3, 5),
                'temperamento'       => $temperamentos[array_rand($temperamentos)],
                'castrado'           => (bool) rand(0, 1),
                'marcas_senales'     => 'Chapeta izquierda #' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'procedencia'        => $procedencias[array_rand($procedencias)],
                'estado_sanitario'   => 'Al día',
                'video_youtube'      => null,
                'estado'             => 'activo',
                'destacado'          => $index < 4, // Los primeros 4 son destacados
                'created_at'         => now(),
                'updated_at'         => now(),
            ]);

            // Insertar 2 fotos placeholder por publicación
            DB::table('mk_publicacion_animal_fotos')->insert([
                [
                    'publicacion_id' => $publicacionId,
                    'url_foto'       => 'https://placehold.co/600x500/e49b39/white?text=Animal+' . ($index + 1),
                    'orden'          => 1,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ],
                [
                    'publicacion_id' => $publicacionId,
                    'url_foto'       => 'https://placehold.co/600x500/c4801e/white?text=Foto+2',
                    'orden'          => 2,
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ],
            ]);
        }
    }
}
