<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Marketplace\MkPublicacionAnimal;
use App\Models\Marketplace\MkPublicacionLote;
use App\Models\Marketplace\MkInsumo;
use App\Models\Marketplace\MkCarrito;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    // ==========================================
    // HOME
    // ==========================================
    public function home()
    {
        // Animales destacados (máx 4)
        $animalesDestacados = MkPublicacionAnimal::with(['fotos', 'animal'])
            ->activos()
            ->destacados()
            ->latest()
            ->take(4)
            ->get();

        // Lotes destacados (máx 3)
        $lotesDestacados = MkPublicacionLote::with(['fotos', 'animales'])
            ->activos()
            ->destacados()
            ->latest()
            ->take(3)
            ->get();

        // Insumos destacados (máx 4)
        $insumosDestacados = MkInsumo::with('fotos')
            ->activos()
            ->destacados()
            ->latest()
            ->take(4)
            ->get();

        // Stats generales
        $stats = [
            'total_animales' => MkPublicacionAnimal::activos()->count(),
            'total_lotes'    => MkPublicacionLote::activos()->count(),
            'total_insumos'  => MkInsumo::activos()->count(),
        ];

        return view('marketplace.home', compact(
            'animalesDestacados',
            'lotesDestacados',
            'insumosDestacados',
            'stats'
        ));
    }

    // ==========================================
    // CATÁLOGO
    // ==========================================
    public function catalogo(Request $request)
    {
        $tipo         = $request->get('tipo', 'todos');
        $precio_min   = $request->get('precio_min');
        $precio_max   = $request->get('precio_max');
        $departamento = $request->get('departamento');
        $proposito    = $request->get('proposito');
        $orden        = $request->get('orden', 'recientes');

        // --- Animales ---
        $animales = collect();
        if ($tipo === 'todos' || $tipo === 'animal') {
            $q = MkPublicacionAnimal::with(['fotos', 'animal.predio'])
                ->activos();

            if ($precio_min) $q->where('precio_venta', '>=', $precio_min);
            if ($precio_max) $q->where('precio_venta', '<=', $precio_max);
            if ($proposito)  $q->where('proposito', $proposito);

            if ($orden === 'precio_asc')  $q->orderBy('precio_venta', 'asc');
            if ($orden === 'precio_desc') $q->orderBy('precio_venta', 'desc');
            if ($orden === 'recientes')   $q->latest();

            $animales = $q->get()->map(function ($item) {
                $item->_tipo = 'animal';
                return $item;
            });
        }

        // --- Lotes ---
        $lotes = collect();
        if ($tipo === 'todos' || $tipo === 'lote') {
            $q = MkPublicacionLote::with(['fotos', 'animales'])
                ->activos();

            if ($precio_min) $q->where('precio', '>=', $precio_min);
            if ($precio_max) $q->where('precio', '<=', $precio_max);
            if ($proposito)  $q->where('proposito', $proposito);

            if ($orden === 'precio_asc')  $q->orderBy('precio', 'asc');
            if ($orden === 'precio_desc') $q->orderBy('precio', 'desc');
            if ($orden === 'recientes')   $q->latest();

            $lotes = $q->get()->map(function ($item) {
                $item->_tipo = 'lote';
                return $item;
            });
        }

        // --- Insumos ---
        $insumos = collect();
        if ($tipo === 'todos' || $tipo === 'insumo') {
            $q = MkInsumo::with('fotos')->activos();

            if ($precio_min) $q->where('precio', '>=', $precio_min);
            if ($precio_max) $q->where('precio', '<=', $precio_max);

            if ($orden === 'precio_asc')  $q->orderBy('precio', 'asc');
            if ($orden === 'precio_desc') $q->orderBy('precio', 'desc');
            if ($orden === 'recientes')   $q->latest();

            $insumos = $q->get()->map(function ($item) {
                $item->_tipo = 'insumo';
                return $item;
            });
        }

        // concat() en lugar de merge() para evitar sobreescritura por claves duplicadas
        $productos = $animales->values()->concat($lotes->values())->concat($insumos->values());

        if ($orden === 'precio_asc') {
            $productos = $productos->sortBy(fn($i) =>
                $i->_tipo === 'animal' ? $i->precio_venta : $i->precio
            );
        } elseif ($orden === 'precio_desc') {
            $productos = $productos->sortByDesc(fn($i) =>
                $i->_tipo === 'animal' ? $i->precio_venta : $i->precio
            );
        } else {
            $productos = $productos->sortByDesc('created_at');
        }

        // Paginación manual
        $perPage = 12;
        $page    = request()->get('page', 1);
        $total   = $productos->count();
        $items   = $productos->forPage($page, $perPage)->values();

        $productos = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Contadores para tabs (totales globales, para referencia en pestañas)
        $contadores = [
            'todos'  => MkPublicacionAnimal::activos()->count()
                      + MkPublicacionLote::activos()->count()
                      + MkInsumo::activos()->count(),
            'animal' => MkPublicacionAnimal::activos()->count(),
            'lote'   => MkPublicacionLote::activos()->count(),
            'insumo' => MkInsumo::activos()->count(),
        ];

        // Total de resultados filtrados (del paginator)
        $totalResultados = $productos->total();

        return view('marketplace.catalogo', compact(
            'productos',
            'contadores',
            'totalResultados',
            'tipo',
            'orden',
            'precio_min',
            'precio_max',
            'departamento',
            'proposito'
        ));
    }

    // ==========================================
    // DETALLE ANIMAL
    // ==========================================
    public function detalleAnimal($id)
    {
        $publicacion = MkPublicacionAnimal::with([
            'fotos',
            'certificados',
            'animal',
            'animal.predio',
            'vendedor',
            'vendedor.perfilMarketplace',
        ])->findOrFail($id);

        // Último peso del animal
        $ultimoPeso = \DB::table('pesaje_animal')
            ->where('id_animal', $publicacion->animal_id)
            ->orderBy('created_at', 'desc')
            ->first();

        // Historial de vacunas/medicación
        $vacunas = \DB::table('medicacion_animal')
            ->where('id_animal', $publicacion->animal_id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        // Otros animales del mismo vendedor
        $relacionados = MkPublicacionAnimal::with('fotos')
            ->where('user_id', $publicacion->user_id)
            ->where('id', '!=', $publicacion->id)
            ->activos()
            ->take(3)
            ->get();

        return view('marketplace.detalle-animal', compact(
            'publicacion',
            'ultimoPeso',
            'vacunas',
            'relacionados'
        ));
    }

    // ==========================================
    // DETALLE LOTE
    // ==========================================
    public function detalleLote($id)
    {
        $publicacion = MkPublicacionLote::with([
            'fotos',
            'certificados',
            'animales.animal',
            'vendedor',
            'vendedor.perfilMarketplace',
        ])->findOrFail($id);

        // Calcular stats del lote desde los animales reales
        $animalesIds = $publicacion->animales->pluck('animal_id');

        $pesoPromedio = \DB::table('pesaje_animal')
            ->whereIn('id_animal', $animalesIds)
            ->selectRaw('AVG(peso) as promedio')
            ->value('promedio');

        $edadPromedio = \DB::table('animales')
            ->whereIn('id_animal', $animalesIds)
            ->selectRaw('AVG(DATEDIFF(NOW(), fecha_nacimiento) / 30) as meses')
            ->value('meses');

        // Otros lotes del mismo vendedor
        $relacionados = MkPublicacionLote::with('fotos')
            ->where('user_id', $publicacion->user_id)
            ->where('id', '!=', $publicacion->id)
            ->activos()
            ->take(3)
            ->get();

        return view('marketplace.detalle-lote', compact(
            'publicacion',
            'pesoPromedio',
            'edadPromedio',
            'relacionados'
        ));
    }

    // ==========================================
    // DETALLE INSUMO
    // ==========================================
    public function detalleInsumo($id)
    {
        $insumo = MkInsumo::with([
            'fotos',
            'vendedor',
            'vendedor.perfilMarketplace',
        ])->findOrFail($id);

        // Insumos relacionados (misma categoría)
        $relacionados = MkInsumo::with('fotos')
            ->where('categoria', $insumo->categoria)
            ->where('id', '!=', $insumo->id)
            ->activos()
            ->take(4)
            ->get();

        return view('marketplace.detalle-insumo', compact(
            'insumo',
            'relacionados'
        ));
    }

    // ==========================================
    // CARRITO
    // ==========================================
    public function carrito()
    {
        // Si está logueado, trae el carrito de BD
        // Si no, carrito vacío (se manejará con localStorage en el frontend)
        $items = collect();
        $total = 0;

        if (auth()->check()) {
            $items = MkCarrito::where('user_id', auth()->id())->get();
            $total = $items->sum(fn ($item) => $item->precio_unitario * $item->cantidad);
        }

        return view('marketplace.carrito', compact('items', 'total'));
    }

    // ==========================================
    // CHECKOUT (protegido por auth en rutas)
    // ==========================================
    public function checkout()
    {
        $items = MkCarrito::where('user_id', auth()->id())->get();

        if ($items->isEmpty()) {
            return redirect()->route('marketplace.carrito')
                ->with('error', 'Tu carrito está vacío.');
        }

        $total = $items->sum(fn ($item) => $item->precio_unitario * $item->cantidad);

        return view('marketplace.checkout', compact('items', 'total'));
    }

    // ==========================================
    // PERFIL VENDEDOR
    // ==========================================
    public function perfilVendedor($id)
    {
        $vendedor = \App\Models\User::with('perfilMarketplace')->findOrFail($id);

        $animales = MkPublicacionAnimal::with('fotos')
            ->where('user_id', $id)
            ->activos()
            ->latest()
            ->get();

        $lotes = MkPublicacionLote::with('fotos')
            ->where('user_id', $id)
            ->activos()
            ->latest()
            ->get();

        $insumos = MkInsumo::with('fotos')
            ->where('user_id', $id)
            ->activos()
            ->latest()
            ->get();

        return view('marketplace.perfil-vendedor', compact(
            'vendedor',
            'animales',
            'lotes',
            'insumos'
        ));
    }

    // ==========================================
    // MIS PEDIDOS (protegido por auth en rutas)
    // ==========================================
    public function misPedidos()
    {
        $pedidos = \App\Models\Marketplace\MkOrden::with(['detalle', 'vendedor', 'vendedor.perfilMarketplace'])
            ->where('comprador_id', auth()->id())
            ->latest()
            ->get();

        return view('marketplace.mis-pedidos', compact('pedidos'));
    }

    // ==========================================
    // PROCESAR CHECKOUT
    // ==========================================
    public function procesarCheckout(Request $request)
    {
        $request->validate([
            'tipo_entrega' => 'required|in:finca,coordinar,envio_domicilio,retiro',
            'metodo_pago'  => 'required|in:transferencia,pse,nequi,efectivo',
            'notas'        => 'nullable|string|max:500',
        ]);

        $items = MkCarrito::where('user_id', auth()->id())->get();

        if ($items->isEmpty()) {
            return redirect()->route('marketplace.carrito')
                ->with('error', 'Tu carrito está vacío.');
        }

        // Agrupar items por vendedor
        $grupos = [];
        foreach ($items as $item) {
            $vendedorId = match ($item->tipo) {
                'animal' => MkPublicacionAnimal::find($item->referencia_id)?->user_id,
                'lote'   => MkPublicacionLote::find($item->referencia_id)?->user_id,
                'insumo' => MkInsumo::find($item->referencia_id)?->user_id,
                default  => null,
            };

            if (!$vendedorId) continue;

            $grupos[$vendedorId][] = $item;
        }

        $ordenIds = [];

        foreach ($grupos as $vendedorId => $itemsGrupo) {
            $total = collect($itemsGrupo)->sum(
                fn ($i) => $i->precio_unitario * $i->cantidad
            );

            $orden = \App\Models\Marketplace\MkOrden::create([
                'comprador_id' => auth()->id(),
                'vendedor_id'  => $vendedorId,
                'total'        => $total,
                'estado'       => 'pendiente',
                'tipo_entrega' => $request->tipo_entrega,
                'metodo_pago'  => $request->metodo_pago,
                'notas'        => $request->notas,
                'wompi_status' => null,
            ]);

            $ordenIds[] = $orden->id;

            // Snapshot de cada item en la orden
            foreach ($itemsGrupo as $item) {
                $nombreSnapshot = match ($item->tipo) {
                    'animal' => (function () use ($item) {
                        $pub = MkPublicacionAnimal::find($item->referencia_id);
                        if (!$pub) return 'Animal #'.$item->referencia_id;
                        return $pub->origen === 'sistema'
                            ? ($pub->animal->nombre ?? 'Animal #'.$pub->animal_id)
                            : $pub->m_nombre;
                    })(),
                    'lote'   => MkPublicacionLote::find($item->referencia_id)?->nombre
                                ?? 'Lote #'.$item->referencia_id,
                    'insumo' => MkInsumo::find($item->referencia_id)?->nombre
                                ?? 'Insumo #'.$item->referencia_id,
                    default  => 'Producto #'.$item->referencia_id,
                };

                \App\Models\Marketplace\MkOrdenDetalle::create([
                    'orden_id'        => $orden->id,
                    'tipo'            => $item->tipo,
                    'referencia_id'   => $item->referencia_id,
                    'cantidad'        => $item->cantidad,
                    'nombre_snapshot' => $nombreSnapshot,
                    'precio_snapshot' => $item->precio_unitario,
                ]);
            }
        }

        // Vaciar carrito
        MkCarrito::where('user_id', auth()->id())->delete();

        // Guardar ids en sesión para la confirmación
        session(['ordenes_recientes' => $ordenIds]);

        return redirect()->route('marketplace.pedido.confirmacion', $ordenIds[0]);
    }

    // ==========================================
    // CONFIRMACIÓN DE PEDIDO
    // ==========================================
    public function confirmacionPedido($id)
    {
        $orden = \App\Models\Marketplace\MkOrden::with([
            'detalle',
            'vendedor',
            'vendedor.perfilMarketplace',
        ])->findOrFail($id);

        if ($orden->comprador_id !== auth()->id()) {
            abort(403);
        }

        // Si se crearon múltiples órdenes (varios vendedores), traerlas todas
        $ordenesRecientes = collect();
        $idsRecientes = session('ordenes_recientes', []);

        if (count($idsRecientes) > 1) {
            $ordenesRecientes = \App\Models\Marketplace\MkOrden::with([
                'detalle',
                'vendedor.perfilMarketplace',
            ])->where('comprador_id', auth()->id())
              ->whereIn('id', $idsRecientes)
              ->get();
        }

        return view('marketplace.confirmacion-pedido', compact(
            'orden',
            'ordenesRecientes'
        ));
    }

    // ==========================================
    // SOLICITAR UPGRADE (comprador → proveedor)
    // ==========================================
    public function solicitarUpgrade()
    {
        if (in_array(auth()->user()->id_rol, [1, 3, 6])) {
            return redirect()->route('marketplace.panel')
                ->with('info', 'Ya tienes acceso para vender en el marketplace.');
        }

        $solicitudPendiente = \App\Models\Marketplace\MkSolicitudUpgrade::where('user_id', auth()->id())
            ->where('estado', 'pendiente')
            ->first();

        return view('marketplace.solicitar-upgrade', compact('solicitudPendiente'));
    }

    public function guardarUpgrade(Request $request)
    {
        $request->validate([
            'mensaje' => 'nullable|string|max:500',
        ]);

        $existe = \App\Models\Marketplace\MkSolicitudUpgrade::where('user_id', auth()->id())
            ->where('estado', 'pendiente')
            ->exists();

        if ($existe) {
            return back()->with('info', 'Ya tienes una solicitud pendiente. Te avisaremos pronto.');
        }

        \App\Models\Marketplace\MkSolicitudUpgrade::create([
            'user_id'        => auth()->id(),
            'rol_actual'     => auth()->user()->id_rol,
            'rol_solicitado' => 6,
            'estado'         => 'pendiente',
            'mensaje'        => $request->mensaje,
        ]);

        return back()->with('success', '¡Solicitud enviada! El equipo de Moollish la revisará pronto.');
    }
}
