<?php

namespace Database\Seeders\Marketplace;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MkInsumoSeeder extends Seeder
{
    public function run()
    {
        $vendedor = DB::table('mk_vendedor_perfil')->first();

        if (!$vendedor) {
            $this->command->warn('No hay vendedores. Ejecuta primero MkVendedorPerfilSeeder.');
            return;
        }

        $insumos = [
            [
                'nombre'            => 'Sales Mineralizadas Premium 25kg',
                'marca'             => 'Procampo',
                'descripcion'       => 'Sales mineralizadas balanceadas para bovinos, equinos y ovinos. Mejora desarrollo óseo y producción láctea.',
                'categoria'         => 'minerales',
                'precio'            => 89000,
                'unidad'            => 'kg',
                'stock'             => 48,
                'registro_ica'      => 'ICA-2024-MN-4521',
                'composicion'       => 'Calcio 18%, Fósforo 9%, Sal 40%, Magnesio 2%, Zinc 0.5%',
                'uso_recomendado'   => 'Bovinos de carne y leche, equinos',
                'dosis'             => '80-100g por animal/día',
                'animales_objetivo' => 'Bovinos, equinos, ovinos',
                'estado'            => 'activo',
                'destacado'         => true,
            ],
            [
                'nombre'            => 'Vacuna Aftosa Dosis 50ml',
                'marca'             => 'Vecol',
                'descripcion'       => 'Vacuna oficial contra la fiebre aftosa. Aplicación obligatoria según calendario ICA.',
                'categoria'         => 'medicamentos',
                'precio'            => 18500,
                'unidad'            => 'unidad',
                'stock'             => 200,
                'registro_ica'      => 'ICA-2023-VA-0012',
                'composicion'       => 'Virus inactivado tipo O, A, C',
                'uso_recomendado'   => 'Bovinos mayores de 3 meses',
                'dosis'             => '2ml subcutáneo',
                'animales_objetivo' => 'Bovinos',
                'estado'            => 'activo',
                'destacado'         => true,
            ],
            [
                'nombre'            => 'Concentrado Engorde Bovino 40kg',
                'marca'             => 'Italcol',
                'descripcion'       => 'Concentrado energético proteico para bovinos en etapa de engorde intensivo.',
                'categoria'         => 'alimentos',
                'precio'            => 120000,
                'unidad'            => 'kg',
                'stock'             => 85,
                'registro_ica'      => 'ICA-2024-AL-3302',
                'composicion'       => 'Proteína cruda 16%, Grasa cruda 3%, Fibra cruda 8%',
                'uso_recomendado'   => 'Bovinos en engorde',
                'dosis'             => '3-4kg por animal/día',
                'animales_objetivo' => 'Bovinos de engorde',
                'estado'            => 'activo',
                'destacado'         => true,
            ],
            [
                'nombre'            => 'Ivermectina Desparasitante 500ml',
                'marca'             => 'Virbac',
                'descripcion'       => 'Antiparasitario de amplio espectro. Controla parásitos internos y externos.',
                'categoria'         => 'medicamentos',
                'precio'            => 45000,
                'unidad'            => 'litro',
                'stock'             => 60,
                'registro_ica'      => 'ICA-2023-ME-1198',
                'composicion'       => 'Ivermectina 1%',
                'uso_recomendado'   => 'Bovinos, equinos, ovinos',
                'dosis'             => '1ml por cada 50kg de peso vivo',
                'animales_objetivo' => 'Bovinos, equinos, ovinos, caprinos',
                'estado'            => 'activo',
                'destacado'         => false,
            ],
            [
                'nombre'            => 'Sal Mineral Bloque 10kg',
                'marca'             => 'Agrosavia',
                'descripcion'       => 'Bloque de sal mineral de consumo libre. Ideal para pastoreo extensivo.',
                'categoria'         => 'minerales',
                'precio'            => 32000,
                'unidad'            => 'unidad',
                'stock'             => 150,
                'registro_ica'      => 'ICA-2024-MN-2210',
                'composicion'       => 'Sal 60%, Calcio 12%, Fósforo 6%, Zinc 0.3%',
                'uso_recomendado'   => 'Todos los bovinos en pastoreo',
                'dosis'             => 'Consumo libre',
                'animales_objetivo' => 'Bovinos, equinos, ovinos',
                'estado'            => 'activo',
                'destacado'         => false,
            ],
        ];

        foreach ($insumos as $insumo) {
            $insumoId = DB::table('mk_insumos')->insertGetId(array_merge(
                $insumo,
                [
                    'user_id'    => $vendedor->user_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ));

            // 1 foto por insumo
            DB::table('mk_insumos_fotos')->insert([
                'insumo_id'  => $insumoId,
                'url_foto'   => 'https://placehold.co/600x500/7a5230/white?text=' . urlencode($insumo['nombre']),
                'orden'      => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
