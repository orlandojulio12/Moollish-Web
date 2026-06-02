<?php

namespace App\Http\Controllers\Marketplace;

use App\Http\Controllers\Controller;
use App\Models\Marketplace\MkPublicacionAnimal;
use App\Models\Marketplace\MkPublicacionAnimalFoto;
use App\Models\Marketplace\MkPublicacionLote;
use App\Models\Marketplace\MkPublicacionLoteAnimal;
use App\Models\Marketplace\MkPublicacionLoteFoto;
use App\Models\Marketplace\MkInsumo;
use App\Models\Marketplace\MkInsumoFoto;
use App\Models\Marketplace\MkOrden;
use App\Models\Marketplace\MkVendedorPerfil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendedorController extends Controller
{
    // ==========================================
    // DASHBOARD
    // ==========================================
    public function dashboard()
    {
        $userId = auth()->id();

        // Stats
        $stats = [
            'publicaciones_activas' =>
                MkPublicacionAnimal::where('user_id', $userId)->activos()->count()
                + MkPublicacionLote::where('user_id', $userId)->activos()->count()
                + MkInsumo::where('user_id', $userId)->activos()->count(),

            'ventas_mes' =>
                MkOrden::where('vendedor_id', $userId)
                    ->where('estado', 'entregado')
                    ->whereMonth('created_at', now()->month)
                    ->sum('total'),

            'ventas_completadas' =>
                MkOrden::where('vendedor_id', $userId)
                    ->where('estado', 'entregado')
                    ->count(),

            'ordenes_pendientes' =>
                MkOrden::where('vendedor_id', $userId)
                    ->where('estado', 'pendiente')
                    ->count(),
        ];

        // Publicaciones recientes (mezcla animal + lote + insumo)
        $animales = MkPublicacionAnimal::with(['fotos', 'animal'])
            ->where('user_id', $userId)
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($item) {
                $item->_tipo   = 'animal';
                $item->_nombre = $item->origen === 'sistema'
                    ? ($item->animal->nombre ?? 'Animal #' . $item->animal_id)
                    : ($item->m_nombre ?? 'Animal #' . $item->id);
                $item->_precio = $item->precio_venta;
                return $item;
            });

        $lotes = MkPublicacionLote::with('fotos')
            ->where('user_id', $userId)
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($item) {
                $item->_tipo   = 'lote';
                $item->_nombre = $item->nombre;
                $item->_precio = $item->precio;
                return $item;
            });

        $insumos = MkInsumo::with('fotos')
            ->where('user_id', $userId)
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($item) {
                $item->_tipo   = 'insumo';
                $item->_nombre = $item->nombre;
                $item->_precio = $item->precio;
                return $item;
            });

        $publicaciones = $animales->merge($lotes)->merge($insumos)
            ->sortByDesc('created_at')
            ->take(5);

        // Últimas ventas
        $ultimasVentas = MkOrden::with(['detalle', 'comprador'])
            ->where('vendedor_id', $userId)
            ->latest()
            ->take(5)
            ->get();

        // Perfil del vendedor
        $perfil = MkVendedorPerfil::where('user_id', $userId)->first();

        return view('marketplace.vendedor.dashboard', compact(
            'stats',
            'publicaciones',
            'ultimasVentas',
            'perfil'
        ));
    }

    // ==========================================
    // CREAR PUBLICACIÓN
    // ==========================================
    public function crearPublicacion()
    {
        $userId = auth()->id();
        $rolId  = auth()->user()->id_rol;

        // Solo propietarios Moollish (rol 3) o admin (rol 1)
        // tienen animales propios en el sistema
        $esMoollish = in_array($rolId, [1, 3]);

        $animalesDisponibles = collect();

        if ($esMoollish) {
            // Animales del usuario que no estén ya publicados activos
            $yaPublicados = \App\Models\Marketplace\MkPublicacionAnimal
                ::where('user_id', $userId)
                ->whereIn('estado', ['activo', 'pausado'])
                ->pluck('animal_id')
                ->filter(); // eliminar nulls (origen manual)

            $animalesDisponibles = \DB::table('animales')
                ->join('predios', 'animales.id_predio', '=', 'predios.id')
                ->join('predios_x_usuario', 'predios.id', '=', 'predios_x_usuario.id_predio')
                ->where('predios_x_usuario.id_usuario', $userId)
                ->whereNotIn('animales.id_animal', $yaPublicados)
                ->where('animales.estado_vida', 1)
                ->select(
                    'animales.id_animal',
                    'animales.nombre',
                    'animales.codigo',
                    'animales.raza',
                    'animales.sexo',
                    'animales.fecha_nacimiento',
                    'animales.peso',
                    'predios.nombre_predio',
                    'predios.municipio',
                    'predios.departamento'
                )
                ->get();
        }

        // Lotes internos del usuario (solo Moollish)
        $lotesInternos = collect();
        if ($esMoollish) {
            $lotesInternos = \DB::table('lotes')
                ->join('predios', 'lotes.predio_id', '=', 'predios.id')
                ->join('predios_x_usuario', 'predios.id', '=', 'predios_x_usuario.id_predio')
                ->where('predios_x_usuario.id_usuario', $userId)
                ->select('lotes.id', 'lotes.nombre', 'predios.nombre_predio')
                ->get();
        }

        // Contadores actuales de publicaciones
        $misPublicaciones = [
            'animales' => \App\Models\Marketplace\MkPublicacionAnimal
                ::where('user_id', $userId)->activos()->count(),
            'lotes'    => \App\Models\Marketplace\MkPublicacionLote
                ::where('user_id', $userId)->activos()->count(),
            'insumos'  => \App\Models\Marketplace\MkInsumo
                ::where('user_id', $userId)->activos()->count(),
        ];

        return view('marketplace.vendedor.crear-publicacion', compact(
            'animalesDisponibles',
            'lotesInternos',
            'misPublicaciones',
            'esMoollish'
        ));
    }

    // ==========================================
    // MIS PUBLICACIONES
    // ==========================================
    public function misPublicaciones()
    {
        $userId = auth()->id();

        $animales = MkPublicacionAnimal::with(['fotos', 'animal'])
            ->where('user_id', $userId)
            ->latest()
            ->get();

        $lotes = MkPublicacionLote::with(['fotos', 'animales'])
            ->where('user_id', $userId)
            ->latest()
            ->get();

        $insumos = MkInsumo::with('fotos')
            ->where('user_id', $userId)
            ->latest()
            ->get();

        return view('marketplace.vendedor.mis-publicaciones', compact(
            'animales',
            'lotes',
            'insumos'
        ));
    }

    // ==========================================
    // MIS VENTAS
    // ==========================================
    public function misVentas()
    {
        $ventas = MkOrden::with(['detalle', 'comprador'])
            ->where('vendedor_id', auth()->id())
            ->latest()
            ->get();

        return view('marketplace.vendedor.mis-ventas', compact('ventas'));
    }

    // ==========================================
    // GUARDAR ANIMAL
    // ==========================================
    public function storeAnimal(Request $request)
    {
        $origen = $request->input('origen', 'manual');

        // Validación según origen
        if ($origen === 'sistema') {
            $request->validate([
                'animal_id'    => 'required|integer',
                'precio_venta' => 'required|numeric|min:0',
                'proposito'    => 'required|in:engorde,leche,reproduccion,sacrificio,cria',
                'fotos'        => 'nullable|array|max:3',
                'fotos.*'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            ]);

            // Verificar que el animal pertenece al usuario
            $perteneceAlUsuario = \DB::table('animales')
                ->join('predios', 'animales.id_predio', '=', 'predios.id')
                ->join('predios_x_usuario', 'predios.id', '=', 'predios_x_usuario.id_predio')
                ->where('predios_x_usuario.id_usuario', auth()->id())
                ->where('animales.id_animal', $request->animal_id)
                ->exists();

            if (!$perteneceAlUsuario) {
                return back()
                    ->withInput()
                    ->with('error', 'No tienes permiso para publicar ese animal.');
            }

            // Verificar que no esté ya publicado
            $yaPublicado = \App\Models\Marketplace\MkPublicacionAnimal
                ::where('animal_id', $request->animal_id)
                ->whereIn('estado', ['activo', 'pausado'])
                ->exists();

            if ($yaPublicado) {
                return back()
                    ->withInput()
                    ->with('error', 'Ese animal ya tiene una publicación activa en el marketplace.');
            }

        } else {
            // Origen manual — validar campos m_*
            $request->validate([
                'm_nombre'       => 'required|string|max:255',
                'm_sexo'         => 'required|in:macho,hembra',
                'm_departamento' => 'required|string|max:100',
                'm_municipio'    => 'required|string|max:100',
                'precio_venta'   => 'required|numeric|min:0',
                'proposito'      => 'required|in:engorde,leche,reproduccion,sacrificio,cria',
                'fotos'          => 'nullable|array|max:3',
                'fotos.*'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            ]);
        }

        // Crear la publicación
        $data = [
            'user_id'            => auth()->id(),
            'origen'             => $origen,
            'animal_id'          => $origen === 'sistema' ? $request->animal_id : null,
            'precio_venta'       => $request->precio_venta,
            'proposito'          => $request->proposito,
            'categoria_animal'   => $request->categoria_animal,
            'condicion_corporal' => $request->condicion_corporal,
            'temperamento'       => $request->temperamento,
            'castrado'           => $request->boolean('castrado'),
            'marcas_senales'     => $request->marcas_senales,
            'procedencia'        => $request->procedencia,
            'estado_sanitario'   => $request->estado_sanitario,
            'video_youtube'      => $request->video_youtube,
            'estado'             => 'activo',
            'destacado'          => false,
        ];

        // Campos manuales solo si origen = manual
        if ($origen === 'manual') {
            $data['m_nombre']       = $request->m_nombre;
            $data['m_raza']         = $request->m_raza;
            $data['m_sexo']         = $request->m_sexo;
            $data['m_peso_kg']      = $request->m_peso_kg;
            $data['m_edad_anos']    = $request->m_edad_anos;
            $data['m_edad_meses']   = $request->m_edad_meses;
            $data['m_color']        = $request->m_color;
            $data['m_hierro']       = $request->m_hierro;
            $data['m_id_sinigan']   = $request->m_id_sinigan;
            $data['m_departamento'] = $request->m_departamento;
            $data['m_municipio']    = $request->m_municipio;
        }

        $publicacion = \App\Models\Marketplace\MkPublicacionAnimal::create($data);

        // Guardar fotos
        if ($request->hasFile('fotos')) {
            $orden = 1;
            foreach ($request->file('fotos') as $foto) {
                if ($foto && $foto->isValid()) {
                    $path = $foto->store('marketplace/animales', 'public');
                    \App\Models\Marketplace\MkPublicacionAnimalFoto::create([
                        'publicacion_id' => $publicacion->id,
                        'url_foto'       => 'storage/' . $path,
                        'orden'          => $orden,
                    ]);
                    $orden++;
                    if ($orden > 3) break;
                }
            }
        }

        return redirect()
            ->route('marketplace.panel')
            ->with('success', '¡Animal publicado exitosamente en el marketplace!');
    }

    // ==========================================
    // GUARDAR LOTE
    // ==========================================
    public function storeLote(Request $request)
    {
        $origen = $request->input('origen', 'manual');

        if ($origen === 'sistema') {
            $request->validate([
                'lote_interno_id' => 'required|integer',
                'precio'          => 'required|numeric|min:0',
                'precio_tipo'     => 'required|in:por_cabeza,total',
                'proposito'       => 'required|in:engorde,leche,reproduccion,mixto',
                'fotos'           => 'nullable|array|max:3',
                'fotos.*'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            ]);

            // Verificar que el lote pertenece al usuario
            $loteInterno = \DB::table('lotes')
                ->join('predios', 'lotes.predio_id', '=', 'predios.id')
                ->join('predios_x_usuario', 'predios.id', '=', 'predios_x_usuario.id_predio')
                ->where('predios_x_usuario.id_usuario', auth()->id())
                ->where('lotes.id', $request->lote_interno_id)
                ->select('lotes.id', 'lotes.nombre')
                ->first();

            if (!$loteInterno) {
                return back()->withInput()
                    ->with('error', 'No tienes permiso para publicar ese lote.');
            }

        } else {
            $request->validate([
                'nombre'          => 'required|string|max:255',
                'precio'          => 'required|numeric|min:0',
                'precio_tipo'     => 'required|in:por_cabeza,total',
                'proposito'       => 'required|in:engorde,leche,reproduccion,mixto',
                'm_num_animales'  => 'required|integer|min:2',
                'm_departamento'  => 'required|string|max:100',
                'm_municipio'     => 'required|string|max:100',
                'fotos'           => 'nullable|array|max:3',
                'fotos.*'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            ]);
        }

        // Nombre del lote
        $nombre = $origen === 'sistema'
            ? ($loteInterno->nombre ?? 'Lote #'.$request->lote_interno_id)
            : $request->nombre;

        // Crear publicación de lote
        $data = [
            'user_id'           => auth()->id(),
            'origen'            => $origen,
            'lote_interno_id'   => $origen === 'sistema' ? $request->lote_interno_id : null,
            'nombre'            => $nombre,
            'precio_tipo'       => $request->precio_tipo,
            'precio'            => $request->precio,
            'proposito'         => $request->proposito,
            'punto_entrega'     => $request->punto_entrega,
            'responsable_flete' => $request->responsable_flete,
            'acepta_visita'     => $request->boolean('acepta_visita'),
            'condicion_pago'    => $request->condicion_pago,
            'video_youtube'     => $request->video_youtube,
            'estado'            => 'activo',
            'destacado'         => false,
            // Campos m_* solo para origen manual
            'm_num_animales'      => $origen === 'manual' ? $request->m_num_animales : null,
            'm_peso_promedio'     => $origen === 'manual' ? $request->peso_promedio   : null,
            'm_edad_promedio'     => $origen === 'manual' ? $request->edad_promedio   : null,
            'm_raza_predominante' => $origen === 'manual' ? $request->raza            : null,
            'm_departamento'      => $origen === 'manual' ? $request->m_departamento  : null,
            'm_municipio'         => $origen === 'manual' ? $request->m_municipio     : null,
        ];

        $lote = MkPublicacionLote::create($data);

        // Copiar animales del lote interno (solo origen sistema)
        if ($origen === 'sistema') {
            $animalesDelLote = \DB::table('animales')
                ->join('lote_animal', 'animales.id_animal', '=', 'lote_animal.id_animal')
                ->where('lote_animal.id_lote', $request->lote_interno_id)
                ->where('animales.estado_vida', 1)
                ->pluck('animales.id_animal');

            foreach ($animalesDelLote as $animalId) {
                MkPublicacionLoteAnimal::firstOrCreate([
                    'publicacion_lote_id' => $lote->id,
                    'animal_id'           => $animalId,
                ]);
            }
        }

        // Guardar fotos
        if ($request->hasFile('fotos')) {
            $orden = 1;
            foreach ($request->file('fotos') as $foto) {
                if ($foto && $foto->isValid()) {
                    $path = $foto->store('marketplace/lotes', 'public');
                    MkPublicacionLoteFoto::create([
                        'publicacion_lote_id' => $lote->id,
                        'url_foto'            => 'storage/' . $path,
                        'orden'               => $orden,
                    ]);
                    $orden++;
                    if ($orden > 3) break;
                }
            }
        }

        return redirect()
            ->route('marketplace.panel')
            ->with('success', '¡Lote publicado exitosamente en el marketplace!');
    }

    // ==========================================
    // GUARDAR INSUMO
    // ==========================================
    public function storeInsumo(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:255',
            'categoria' => 'required|in:minerales,medicamentos,alimentos,equipos,otros',
            'precio'    => 'required|numeric|min:0',
            'unidad'    => 'required|string|max:50',
            'stock'     => 'required|integer|min:0',
            'fotos'     => 'nullable|array|max:3',
            'fotos.*'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        // Crear el insumo
        $insumo = MkInsumo::create([
            'user_id'           => auth()->id(),
            'nombre'            => $request->nombre,
            'marca'             => $request->marca,
            'descripcion'       => $request->descripcion,
            'categoria'         => $request->categoria,
            'precio'            => $request->precio,
            'unidad'            => $request->unidad,
            'stock'             => $request->stock,
            'registro_ica'      => $request->registro_ica,
            'composicion'       => $request->composicion,
            'uso_recomendado'   => $request->uso_recomendado,
            'dosis'             => $request->dosis,
            'animales_objetivo' => $request->animales_objetivo,
            'estado'            => 'activo',
            'destacado'         => false,
        ]);

        // Guardar fotos
        if ($request->hasFile('fotos')) {
            $orden = 1;
            foreach ($request->file('fotos') as $foto) {
                if ($foto && $foto->isValid()) {
                    $path = $foto->store('marketplace/insumos', 'public');

                    MkInsumoFoto::create([
                        'insumo_id' => $insumo->id,
                        'url_foto'  => 'storage/' . $path,
                        'orden'     => $orden,
                    ]);

                    $orden++;
                    if ($orden > 3) break;
                }
            }
        }

        return redirect()
            ->route('marketplace.panel')
            ->with('success', '¡Insumo publicado exitosamente en el marketplace!');
    }

    // ==========================================
    // AJAX: DATOS DE LOTE INTERNO
    // ==========================================
    public function datosLoteInterno($id)
    {
        // Verificar que el lote pertenece al usuario autenticado
        $perteneceAlUsuario = \DB::table('lotes')
            ->join('predios', 'lotes.predio_id', '=', 'predios.id')
            ->join('predios_x_usuario', 'predios.id', '=', 'predios_x_usuario.id_predio')
            ->where('predios_x_usuario.id_usuario', auth()->id())
            ->where('lotes.id', $id)
            ->exists();

        if (!$perteneceAlUsuario) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        // Obtener animales del lote
        $animales = \DB::table('animales')
            ->join('lote_animal', 'animales.id_animal', '=', 'lote_animal.id_animal')
            ->where('lote_animal.id_lote', $id)
            ->where('animales.estado_vida', 1)
            ->select(
                'animales.id_animal',
                'animales.nombre',
                'animales.raza',
                'animales.sexo',
                'animales.peso',
                'animales.fecha_nacimiento'
            )
            ->get();

        if ($animales->isEmpty()) {
            return response()->json([
                'num_animales'        => 0,
                'peso_promedio'       => 0,
                'edad_promedio_meses' => 0,
                'animales'            => [],
            ]);
        }

        // Enriquecer con último pesaje de cada animal
        $animalesData = $animales->map(function ($a) {
            $ultimoPeso = \DB::table('pesaje_animal')
                ->where('id_animal', $a->id_animal)
                ->orderByDesc('created_at')
                ->value('peso');

            $pesoFinal = $ultimoPeso ?? $a->peso ?? 0;

            $edadMeses = 0;
            if ($a->fecha_nacimiento) {
                $nac       = \Carbon\Carbon::parse($a->fecha_nacimiento);
                $edadMeses = (int) $nac->diffInMonths(now());
            }

            return [
                'id_animal'   => $a->id_animal,
                'nombre'      => $a->nombre,
                'raza'        => $a->raza,
                'sexo'        => $a->sexo,
                'peso'        => $pesoFinal,
                'edad_meses'  => $edadMeses,
            ];
        });

        $numAnimales       = $animalesData->count();
        $pesoPromedio      = $numAnimales > 0
            ? round($animalesData->avg('peso'), 1)
            : 0;
        $edadPromedioMeses = $numAnimales > 0
            ? (int) round($animalesData->avg('edad_meses'))
            : 0;

        return response()->json([
            'num_animales'        => $numAnimales,
            'peso_promedio'       => $pesoPromedio,
            'edad_promedio_meses' => $edadPromedioMeses,
            'animales'            => $animalesData->values(),
        ]);
    }

    // ==========================================
    // MI PERFIL DE VENDEDOR
    // ==========================================
    public function miPerfil()
    {
        $perfil  = MkVendedorPerfil::firstOrNew(['user_id' => auth()->id()]);
        $predios = auth()->user()->predios;

        return view('marketplace.vendedor.mi-perfil', compact('perfil', 'predios'));
    }

    public function actualizarPerfil(Request $request)
    {
        $predios    = auth()->user()->predios;
        $tienePredio = $predios->isNotEmpty();

        if ($tienePredio) {
            // Solo descripcion y whatsapp son editables cuando hay predios
            $request->validate([
                'descripcion' => 'nullable|string|max:1000',
                'whatsapp'    => 'nullable|string|max:20',
            ]);

            $predio = $predios->first();

            MkVendedorPerfil::updateOrCreate(
                ['user_id' => auth()->id()],
                [
                    'nombre_finca' => $predio->nombre_predio,
                    'departamento' => $predio->departamento,
                    'municipio'    => $predio->municipio,
                    'whatsapp'     => $request->whatsapp,
                    'descripcion'  => $request->descripcion,
                    'activo'       => true,
                ]
            );
        } else {
            $request->validate([
                'nombre_finca' => 'required|string|max:255',
                'departamento' => 'required|string|max:100',
                'municipio'    => 'required|string|max:100',
                'whatsapp'     => 'nullable|string|max:20',
                'descripcion'  => 'nullable|string|max:1000',
            ]);

            MkVendedorPerfil::updateOrCreate(
                ['user_id' => auth()->id()],
                [
                    'nombre_finca' => $request->nombre_finca,
                    'departamento' => $request->departamento,
                    'municipio'    => $request->municipio,
                    'whatsapp'     => $request->whatsapp,
                    'descripcion'  => $request->descripcion,
                    'activo'       => true,
                ]
            );
        }

        return back()->with('success', '¡Perfil de vendedor actualizado correctamente!');
    }
}
