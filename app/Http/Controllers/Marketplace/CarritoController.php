<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Marketplace\MkCarrito;
use App\Models\Marketplace\MkPublicacionAnimal;
use App\Models\Marketplace\MkPublicacionLote;
use App\Models\Marketplace\MkInsumo;
use Illuminate\Http\Request;

class CarritoController extends Controller
{
    // ── GET /marketplace/api/carrito ──────────────────────────────
    public function index()
    {
        $items = MkCarrito::where('user_id', auth()->id())->get();
        return response()->json($this->enriquecer($items));
    }

    // ── GET /marketplace/api/carrito/count ───────────────────────
    public function count()
    {
        return response()->json([
            'count' => MkCarrito::where('user_id', auth()->id())->count(),
        ]);
    }

    // ── POST /marketplace/api/carrito/agregar ────────────────────
    public function agregar(Request $request)
    {
        $request->validate([
            'tipo'          => 'required|in:animal,lote,insumo',
            'referencia_id' => 'required|integer',
            'cantidad'      => 'required|integer|min:1',
        ]);

        $precio = $this->obtenerPrecio($request->tipo, $request->referencia_id);
        if ($precio === null) {
            return response()->json(['error' => 'Producto no encontrado.'], 404);
        }

        $existente = MkCarrito::where('user_id', auth()->id())
            ->where('tipo', $request->tipo)
            ->where('referencia_id', $request->referencia_id)
            ->first();

        if ($existente) {
            if ($request->tipo === 'insumo') {
                $existente->increment('cantidad', $request->cantidad);
            } else {
                return response()->json(['error' => 'Este item ya está en tu carrito.'], 409);
            }
        } else {
            MkCarrito::create([
                'user_id'         => auth()->id(),
                'tipo'            => $request->tipo,
                'referencia_id'   => $request->referencia_id,
                'cantidad'        => $request->cantidad,
                'precio_unitario' => $precio,
            ]);
        }

        return response()->json([
            'message' => 'Agregado al carrito.',
            'count'   => MkCarrito::where('user_id', auth()->id())->count(),
        ]);
    }

    // ── DELETE /marketplace/api/carrito/{id} ─────────────────────
    public function quitar($id)
    {
        MkCarrito::where('user_id', auth()->id())->where('id', $id)->delete();

        return response()->json([
            'message' => 'Item eliminado.',
            'count'   => MkCarrito::where('user_id', auth()->id())->count(),
        ]);
    }

    // ── PATCH /marketplace/api/carrito/{id}/cantidad ─────────────
    public function actualizarCantidad(Request $request, $id)
    {
        $request->validate(['cantidad' => 'required|integer|min:1']);

        $item = MkCarrito::where('user_id', auth()->id())->where('id', $id)->firstOrFail();

        if ($item->tipo !== 'insumo') {
            return response()->json(['error' => 'Solo puedes cambiar la cantidad en insumos.'], 422);
        }

        $item->update(['cantidad' => $request->cantidad]);

        return response()->json([
            'subtotal' => (float) ($item->precio_unitario * $request->cantidad),
            'count'    => MkCarrito::where('user_id', auth()->id())->count(),
        ]);
    }

    // ── DELETE /marketplace/api/carrito/vaciar ───────────────────
    public function vaciar()
    {
        MkCarrito::where('user_id', auth()->id())->delete();
        return response()->json(['message' => 'Carrito vaciado.', 'count' => 0]);
    }

    // ── POST /marketplace/api/carrito/fusionar ───────────────────
    // Merges guest localStorage cart into DB after login
    public function fusionar(Request $request)
    {
        $request->validate([
            'items'                  => 'required|array',
            'items.*.tipo'           => 'required|in:animal,lote,insumo',
            'items.*.referencia_id'  => 'required|integer',
            'items.*.cantidad'       => 'required|integer|min:1',
        ]);

        foreach ($request->items as $gi) {
            $precio = $this->obtenerPrecio($gi['tipo'], $gi['referencia_id']);
            if ($precio === null) continue;

            $existente = MkCarrito::where('user_id', auth()->id())
                ->where('tipo', $gi['tipo'])
                ->where('referencia_id', $gi['referencia_id'])
                ->first();

            if ($existente && $gi['tipo'] === 'insumo') {
                $existente->increment('cantidad', $gi['cantidad']);
            } elseif (!$existente) {
                MkCarrito::create([
                    'user_id'         => auth()->id(),
                    'tipo'            => $gi['tipo'],
                    'referencia_id'   => $gi['referencia_id'],
                    'cantidad'        => $gi['cantidad'],
                    'precio_unitario' => $precio,
                ]);
            }
        }

        return response()->json([
            'message' => 'Carrito fusionado.',
            'count'   => MkCarrito::where('user_id', auth()->id())->count(),
        ]);
    }

    // ── Helpers ──────────────────────────────────────────────────

    private function obtenerPrecio(string $tipo, int $id): ?float
    {
        if ($tipo === 'animal') {
            return (float) (MkPublicacionAnimal::find($id)?->precio_venta);
        }

        if ($tipo === 'lote') {
            $lote = MkPublicacionLote::with('animales')->find($id);
            if (!$lote) return null;
            // Siempre guardamos el precio TOTAL del lote en el carrito
            return $lote->precio_tipo === 'por_cabeza'
                ? (float) $lote->precio * $lote->animales->count()
                : (float) $lote->precio;
        }

        if ($tipo === 'insumo') {
            return (float) (MkInsumo::find($id)?->precio);
        }

        return null;
    }

    public function enriquecer($items): array
    {
        return $items->map(function ($item) {
            $base = [
                'id'              => $item->id,
                'tipo'            => $item->tipo,
                'referencia_id'   => $item->referencia_id,
                'cantidad'        => $item->cantidad,
                'precio_unitario' => (float) $item->precio_unitario,
                'subtotal'        => (float) ($item->precio_unitario * $item->cantidad),
                'nombre'          => '—',
                'foto_url'        => null,
                'vendedor'        => null,
                'detalle'         => null,
            ];

            if ($item->tipo === 'animal') {
                $pub = MkPublicacionAnimal::with(['fotos', 'animal', 'vendedor.perfilMarketplace'])
                    ->find($item->referencia_id);
                if ($pub) {
                    $base['nombre']   = $pub->animal->nombre ?? 'Animal #'.$pub->animal_id;
                    $base['foto_url'] = $pub->fotos->first()?->url_foto
                        ? asset($pub->fotos->first()->url_foto) : null;
                    $base['vendedor'] = $pub->vendedor?->perfilMarketplace?->nombre_finca
                        ?? $pub->vendedor?->name;
                    $parts = array_filter([
                        $pub->animal->raza ?? null,
                        ($pub->animal->peso ?? null) ? $pub->animal->peso.'kg' : null,
                    ]);
                    $base['detalle'] = implode(' · ', $parts);
                }
            } elseif ($item->tipo === 'lote') {
                $pub = MkPublicacionLote::with(['fotos', 'animales', 'vendedor.perfilMarketplace'])
                    ->find($item->referencia_id);
                if ($pub) {
                    $numAnimales = $pub->animales->count();
                    $base['nombre']   = $pub->nombre;
                    $base['foto_url'] = $pub->fotos->first()?->url_foto
                        ? asset($pub->fotos->first()->url_foto) : null;
                    $base['vendedor'] = $pub->vendedor?->perfilMarketplace?->nombre_finca
                        ?? $pub->vendedor?->name;
                    // Detalle explica el precio total
                    if ($pub->precio_tipo === 'por_cabeza') {
                        $base['detalle'] = $numAnimales.' cabezas × $'.number_format($pub->precio, 0, ',', '.').' c/u';
                    } else {
                        $base['detalle'] = $numAnimales.' cabezas — precio total del lote';
                    }
                }
            } elseif ($item->tipo === 'insumo') {
                $ins = MkInsumo::with(['fotos', 'vendedor.perfilMarketplace'])
                    ->find($item->referencia_id);
                if ($ins) {
                    $base['nombre']   = $ins->nombre;
                    $base['foto_url'] = $ins->fotos->first()?->url_foto
                        ? asset($ins->fotos->first()->url_foto) : null;
                    $base['vendedor'] = $ins->vendedor?->perfilMarketplace?->nombre_finca
                        ?? $ins->vendedor?->name;
                    $base['detalle']  = $ins->marca ?? null;
                }
            }

            return $base;
        })->values()->toArray();
    }
}
