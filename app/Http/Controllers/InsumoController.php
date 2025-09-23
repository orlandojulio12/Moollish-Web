<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use App\Models\InventarioInsumo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InsumoController extends Controller
{
    public function getInsumosPorPredio($predioId)
    {
        try {
            // Obtenemos los insumos que pertenecen al predio y tienen inventario
            $insumos = Insumo::where('predio_id', $predioId)
                ->whereHas('inventario', function($query) {
                    $query->where('cantidad', '>', 0);
                })
                ->with(['inventario' => function($query) {
                    $query->where('cantidad', '>', 0);
                }])
                ->get();

            // Mapeamos los resultados
            $insumosFormateados = $insumos->map(function($insumo) {
                $inventario = $insumo->inventario->first();
                return [
                    'id' => $insumo->id,
                    'codigo' => $insumo->codigo,
                    'nombre' => $insumo->nombre_comercial,
                    'categoria' => $insumo->categoria ? $insumo->categoria->nombre : 'Sin categoría',
                    'stock' => $inventario ? $inventario->cantidad : 0,
                    'unidad_medida' => $insumo->unidad_medida
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $insumosFormateados
            ]);

        } catch (\Exception $e) {
            Log::error('Error al obtener insumos del predio: ' . $e->getMessage());
            Log::error($e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los insumos del predio',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
