<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MarketplaceSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            // ORDEN IMPORTANTE — respetar dependencias
            Marketplace\MkVendedorPerfilSeeder::class,    // 1. Primero perfiles
            Marketplace\MkPublicacionAnimalSeeder::class, // 2. Animales individuales
            Marketplace\MkPublicacionLoteSeeder::class,   // 3. Lotes
            Marketplace\MkInsumoSeeder::class,            // 4. Insumos
            Marketplace\MkOrdenSeeder::class,             // 5. Órdenes de prueba
        ]);
    }
}
