<?php

namespace Database\Seeders\Marketplace;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MkOrdenSeeder extends Seeder
{
    public function run()
    {
        // Necesitamos al menos 2 usuarios: comprador y vendedor
        $users = DB::table('users')->take(2)->get();

        if ($users->count() < 2) {
            $this->command->warn('Se necesitan al menos 2 usuarios en la BD.');
            return;
        }

        $comprador = $users[0];
        $vendedor  = $users[1];

        $ordenes = [
            [
                'estado'       => 'entregado',
                'tipo_entrega' => 'finca',
                'metodo_pago'  => 'transferencia',
                'total'        => 81000000,
                'notas'        => 'Coordinar retiro el día lunes.',
                'tipo_item'    => 'lote',
                'nombre_item'  => 'Lote 18 Novillas Holstein',
                'precio_item'  => 81000000,
            ],
            [
                'estado'       => 'coordinacion',
                'tipo_entrega' => 'coordinar',
                'metodo_pago'  => 'pse',
                'total'        => 5200000,
                'notas'        => null,
                'tipo_item'    => 'animal',
                'nombre_item'  => 'Toro Brahman Rojo #B-1042',
                'precio_item'  => 5200000,
            ],
            [
                'estado'       => 'pendiente',
                'tipo_entrega' => 'envio_domicilio',
                'metodo_pago'  => 'nequi',
                'total'        => 178000,
                'notas'        => 'Enviar a vereda El Carmen.',
                'tipo_item'    => 'insumo',
                'nombre_item'  => 'Sales Mineralizadas Premium 25kg x2',
                'precio_item'  => 89000,
            ],
        ];

        foreach ($ordenes as $orden) {
            $ordenId = DB::table('mk_ordenes')->insertGetId([
                'comprador_id'         => $comprador->id,
                'vendedor_id'          => $vendedor->id,
                'total'                => $orden['total'],
                'estado'               => $orden['estado'],
                'tipo_entrega'         => $orden['tipo_entrega'],
                'metodo_pago'          => $orden['metodo_pago'],
                'wompi_transaction_id' => null,
                'wompi_status'         => $orden['estado'] === 'entregado' ? 'APPROVED' : null,
                'wompi_reference'      => 'MKT-' . now()->year . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                'notas'                => $orden['notas'],
                'created_at'           => now()->subDays(rand(1, 30)),
                'updated_at'           => now(),
            ]);

            DB::table('mk_ordenes_detalle')->insert([
                'orden_id'        => $ordenId,
                'tipo'            => $orden['tipo_item'],
                'referencia_id'   => 1,
                'nombre_snapshot' => $orden['nombre_item'],
                'precio_snapshot' => $orden['precio_item'],
                'cantidad'        => $orden['tipo_item'] === 'insumo' ? 2 : 1,
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);
        }
    }
}
