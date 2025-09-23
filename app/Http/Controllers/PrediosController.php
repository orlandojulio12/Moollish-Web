<?php

namespace App\Http\Controllers;

use App\Models\Predios;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\PrediosRequest;
use App\Models\Areas;
use App\Models\InfoTierraAgua;
use App\Models\ManGenGanado;
use App\Models\ManPastPotrCer;
use App\Models\Municipio;
use App\Models\Propietario;
use App\Models\RazasGanado;
use App\Models\PredioParametro;
use App\Models\User;
use App\Models\TiposAreas;
use App\Models\InstalacionesEquipos;
use App\Models\AsignPrediosEncuestador;
use App\Models\TiposInstalacionesEquipo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Profiler\Profile;
use Illuminate\Http\JsonResponse;

class PrediosController extends Controller
{

    public function index(Request $request): View
    {
        $user = Auth::user();

        if ($user->role->name === 'admin') {
            // Administrador ve todos los predios
            $predios = Predios::with('usuarios')->get();
        } elseif ($user->role->name === 'propietario') {
            // 1. Predios asignados directamente al usuario
            $predios = $user->predios()->with('usuarios')->get();
            $fuenteDirectaIds = $predios->pluck('id')->toArray(); // Guardar IDs Fuente 1

            // 2. Predios que el propietario ha creado (usando la propiedad created_by)
            $prediosCreadosPorEl = Predios::where('created_by', $user->id)->with('usuarios')->get();
            $fuenteCreadosPorElIds = $prediosCreadosPorEl->pluck('id')->toArray(); // Guardar IDs Fuente 2

            // 3. Predios asignados a los usuarios que el propietario ha creado
            $usuariosCreados = User::where('created_by', $user->id)->pluck('id');
            $prediosCreados = Predios::whereHas('usuarios', function ($query) use ($usuariosCreados) {
                $query->whereIn('predios_x_usuario.id_usuario', $usuariosCreados);
            })->with('usuarios')->get();
            $fuenteUsuariosCreadosIds = $prediosCreados->pluck('id')->toArray(); // Guardar IDs Fuente 3

            // 4. Predios asignados al creador del usuario (la "inversión")
            $fuenteCreadorIds = []; // Inicializar array para Fuente 4
            if ($user->created_by) {
                $prediosCreador = Predios::whereHas('usuarios', function ($query) use ($user) {
                    $query->where('predios_x_usuario.id_usuario', $user->created_by);
                })->with('usuarios')->get();
                $fuenteCreadorIds = $prediosCreador->pluck('id')->toArray(); // Guardar IDs Fuente 4
            }

            // *** Dump and Die ANTES de fusionar ***
      /*       dd([
                'Fuente 1 (Asignados Directamente)' => $fuenteDirectaIds,
                'Fuente 2 (Creados por Usuario)' => $fuenteCreadosPorElIds,
                'Fuente 3 (Asignados a Usuarios Creados)' => $fuenteUsuariosCreadosIds,
                'Fuente 4 (Asignados al Creador del Usuario)' => $fuenteCreadorIds,
            ]); */

            // Eliminar duplicados, si existieran
            $predios = $predios->merge($prediosCreadosPorEl);
            $predios = $predios->merge($prediosCreados);
        } elseif ($user->role->name === 'encuestador') {
            // Encuestador ve los predios asignados a él a través de 'asign_predios_encuestador'
            $asignaciones = AsignPrediosEncuestador::where('id_encuestador', $user->id)->pluck('id_predio');
            $predios = Predios::with('usuarios')->whereIn('id', $asignaciones)->get();
        } else {
            // Para otros roles, se retorna una colección vacía o se ajusta según sea necesario
            $predios = collect();
        }

        // Calcular estadísticas para cada predio individual
        foreach($predios as $predio) {
            // Total de animales del predio
            $predio->total_animales = DB::table('animales')
                ->where('id_predio', $predio->id)
                ->count();

            // Total de potreros del predio
            $predio->total_potreros = DB::table('potreros')
                ->where('predio_id', $predio->id)
                ->count();

            // Total de instalaciones del predio
            $predio->total_lotes = DB::table('lotes')
                ->where('predio_id', $predio->id)

                ->count();
        }

        return view('Predios.index', compact('predios'));
    }

    public function verificarCodigoPredio(Request $request)
    {
        $codigoPredio = $request->input('cod_predio');

        $existe = Predios::where('cod_predio', $codigoPredio)->exists();

        return response()->json(['exists' => $existe]);
    }


    public function create()
    {
        $Predios = new Predios();

        // Obtener los departamentos disponibles
        $departamentos = DB::table('municipio')
            ->select(DB::raw('MIN(id) as id'), 'departamento')
            ->groupBy('departamento')
            ->get();

        // Obtener el usuario autenticado
        $user = Auth::user();

        // Inicializar variable de propietarios
        $propietarios = collect();

        // Verificar rol del usuario autenticado
        if ($user->role->name === 'admin') {
            // Si es admin, obtener todos los usuarios con rol "propietario"
            $propietarios = User::where('id_rol', 3)->get();
        } elseif ($user->role->name === 'propietario') {
            // Si es propietario, obtener predios asociados
            $prediosIds = $user->predios->pluck('id')->toArray();

            // Obtener usuarios con predios compartidos o creados por el usuario autenticado
            $propietarios = User::where('id_rol', 3)
                ->where(function ($query) use ($prediosIds, $user) {
                    $query->whereHas('predios', function ($subQuery) use ($prediosIds) {
                        $subQuery->whereIn('predios.id', $prediosIds); // Usuarios con predios compartidos
                    })
                    ->orWhere('created_by', $user->id); // Usuarios creados por el usuario actual
                })
                ->get();

            // Incluir al usuario autenticado como propietario si no está en la colección
            if (!$propietarios->contains('id', $user->id)) {
                $propietarios->push($user);
            }
        }

        // Renderizar la vista con las variables necesarias
        return view('Predios.create', compact('Predios', 'departamentos', 'propietarios'));
    }


    public function getMunicipiosByDepartamento($departamento)
    {
        // Obtener el nombre del departamento usando el ID
        $departamentos = Municipio::where('id', $departamento)->first();

        $municipios = Municipio::where('departamento', $departamentos->departamento)->get();
        return response()->json($municipios);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        // Reglas de validación actualizadas para recibir un arreglo de usuarios
        $rules = [
            'id_usuario'    => ['required', 'array'],
            'id_usuario.*'  => ['exists:users,id'],
            'cod_predio'    => ['required', 'string', 'max:255', 'unique:predios,cod_predio'],
            'departamento'  => ['required', 'string', 'max:255'],
            'municipio'     => ['required', 'string', 'max:255'],
            'nombre_predio' => ['nullable', 'string', 'max:255'],
            'vereda'        => ['nullable', 'string', 'max:255'],
            'forma_de_llegar' => ['nullable', 'string', 'max:255'],
            'latitud'       => ['nullable', 'numeric'],
            'longitud'      => ['nullable', 'numeric'],
        ];

        try {
            // Validar los datos recibidos
            $validatedData = $request->validate($rules);
            $created_by = Auth::user()->id;

            // Crear el predio asignando el usuario que lo crea
            $predio = Predios::create(array_merge($validatedData, [
                'created_by' => $created_by
            ]));

            // Guardar la relación en la tabla pivote para cada propietario seleccionado
            if (!empty($validatedData['id_usuario'])) {
                $predio->usuarios()->attach($validatedData['id_usuario']);
            }

            if ($request->ajax() || $request->wantsJson() || $request->input('is_sync') === 'true') {
                return response()->json([
                    'success' => true,
                    'id'      => $predio->id,
                    'message' => 'Predio creado exitosamente con su propietario asociado.'
                ]);
            }

            // Si es form normal, redirigir
            return redirect()
                ->route('predios.index')
                ->with('success', 'Predio creado exitosamente con su propietario asociado.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // -------------------------------
            // Errores de validación
            // -------------------------------
            if ($request->ajax() || $request->wantsJson() || $request->input('is_sync') === 'true') {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors'  => $e->errors()
                ], 422);
            }

            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            // -------------------------------
            // Errores generales
            // -------------------------------
            Log::error("Error al crear el predio: " . $e->getMessage());

            if ($request->ajax() || $request->wantsJson() || $request->input('is_sync') === 'true') {
                return response()->json([
                    'success' => false,
                    'message' => 'Error interno del servidor al crear el predio.'
                ], 500);
            }

            return redirect()
                ->back()
                ->with('error', 'Hubo un error al crear el predio. Por favor, inténtalo nuevamente.');
        }
    }



    public function caracterizar($id)
    {
        // Encuentra el predio
        $Predios = Predios::findOrFail($id);
        $predioId = Predios::findOrFail($id)->id;


        // Recupera las áreas ya asignadas al predio
        $areasExistentes = Areas::where('id_predio', $id)->get();

        $caratexists = Areas::where('id_predio', $predioId)->exists();


        // Recupera todos los tipos de áreas disponibles
        $tiposAreas = TiposAreas::all();

        return view('Predios.caracterizar', compact('Predios', 'tiposAreas', 'areasExistentes', 'caratexists'));
    }


    public function storeCaracterizacion(Request $request, $id)
    {
        // Valida las entradas, incluyendo la imagen

        $cant_total = 0;
        foreach ($request->areas as $area) {
            $cant_total += $area['medidas'];
        }

        // Procesar cada área
        foreach ($request->areas as $index => $area) {
            // Procesar la imagen para cada área si existe
            $imagenPath = null;
            if (isset($area['imagen']) && $area['imagen'] instanceof \Illuminate\Http\UploadedFile) {
                $imagenPath = $this->moveImage($area['imagen']); // Llama a tu función para mover la imagen
            }

            // Crear las áreas con la imagen incluida si está presente
            Areas::create([
                'id_tipo_area' => $area['id_tipo_area'],
                'id_predio' => $id,
                'medidas' => $area['medidas'],
                'materiales_establecidos' => $area['materiales_establecidos'],
                'tipo_medidas' => $area['tipo_medidas'],
                'cant_total' => $cant_total,
                'imagen' => $imagenPath, // Guardar la ruta de la imagen si está presente
            ]);
        }

        return redirect()->route('Seccion2', ['id' => $id])
            ->with('success', 'Información del predio guardada correctamente');
    }

    private function moveImage($file)
    {
        $nombreImagen = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension(); // Genera un nombre único basado en el timestamp
        $destinoPath = public_path('/imagenes/'); // Define la ruta de destino

        // Crea la carpeta si no existe
        if (!file_exists($destinoPath)) {
            mkdir($destinoPath, 0777, true);
        }

        // Mueve la imagen al destino
        $file->move($destinoPath, $nombreImagen);

        return $nombreImagen; // Retorna el nombre de la imagen para almacenarlo en la base de datos
    }

    public function mostrarGrafica()
    {
        // Consulta para contar predios por municipio
        $prediosPorMunicipio = Predios::select('municipio', DB::raw('count(*) as total'))
            ->groupBy('municipio')
            ->get();

        return view('Predios.grafica', compact('prediosPorMunicipio'));
    }

    public function showSeccion2($id)
    {
        $predios = Predios::findOrFail($id);

        return view('seccion2.seccion2', compact('predios'));
    }

    public function showAsignacion($id)
    {
        $predios = Predios::findOrFail($id);

        return view('seccion2.seccion2', compact('predios'));
    }

    public function showSeccion3($id)
    {
        $predios = Predios::findOrFail($id);

        return view('seccion3.seccion3', compact('predios'));
    }

    public function showSeccion4($id)
    {
        $predios = Predios::findOrFail($id);

        return view('seccion4.seccion4', compact('predios'));
    }

    public function showSeccion6($id)
    {
        $predios = Predios::findOrFail($id);

        return view('seccion6.seccion6', compact('predios'));
    }

    public function showSecciones($id)
    {
        $predios = Predios::findOrFail($id);

        return view('secciones.secciones', compact('predios'));
    }

    public function show($id): View
    {
        $Predios = Predios::select('id', 'nombre_predio', 'id_propietario')->find($id);
        $Areas = Areas::with('TiposAreas')
            ->select('id', 'id_tipo_area', 'medidas', 'materiales_establecidos')
            ->where('id_predio', $id)
            ->get();


        $infoTierraAgua = InfoTierraAgua::with('Predios')
            ->select(
                'id',
                'suelos_predominantes',
                'drenaje',
                'manejo_cuencas_nac_agua',
                'cantidad_preservacion',
                'porcentaje_preservacion',
                'fuente_calidad_agua',
                'fuente_calidad_agua_uso_domestic',
                'disp_agua_durant_veran_anim',
                'disp_agua_durant_veran_anim_fuente',
                'disp_agua_durant_veran_riesg',
                'disp_agua_durant_veran_riesg_fuente'
            )
            ->where('id_predio', $id)
            ->get();

        $manPastPotrCerc = ManPastPotrCer::select(
            'id',
            'area_dest_past',
            'r_fertilazion_potreros',
            'r_fertilazion_potreros_produc',
            'r_fertilazion_potreros_cuant_año',
            'presen_plag_enferm',
            'presen_plag_enferm_tipos',
            'r_control_plagas',
            'r_control_plagas_produc',
            'r_control_plagas_cuant_año',
            'r_control_maleza',
            'r_control_maleza_product',
            'r_control_maleza_cuant_año',
            'precencia_heladas',
            'precencia_heladas_intensidad',
            'precencia_heladas_epocas',
            'div_potreros',
            'div_potreros_como',
            'tipo_pastoreo',
            'rotacional_dias_ocupacion',
            'rotacional_dias_descanso',
            'cercas',
            'cercas_puas',
            'cercas_electricas',
            'la_produccion_forraje_suficiente_año',
            'porque'
        )
            ->where('id_predio', $id)
            ->get();


        $manGenGanado = ManGenGanado::with('razas')
            ->select(
                'id',
                'id_raza_gan',
                'ident_animales',
                'sistema_cria_ternero',
                'aliment_ternero',
                'sistem_levant_animal',
                'manej_hembras_prox',
                'manej_vacas_secas',
                'tipo_ordeño',
                'sistem_servic_reproduct',
                'form_program_servicios',
                'pesaje_animal',
                'cuantos_animal_pesa',
                'control_parasito_extern',
                'control_parasito_extern_produc',
                'control_parasito_extern_frecuenc',
                'control_parasito_intern',
                'control_parasito_intern_produc',
                'control_parasito_intern_frecuenc',
                'sumin_sal',
                'a_sal_add_premezcla',
                'a_sal_add_premezcla_especifique',
                'como_manej_ganad_veran',
                'como_manej_ganad_invier',
                'r_pesaje_leche_hembr_lactantes',
                'r_pesaje_leche_hembr_periodicidad',
                'suplement_ganad_epoc_criti',
                'suplement_ganad_epoc_criti_con_que',
                'suplement_ganad_epoc_criti_que_lotes'
            )
            ->where('id_predio', $id)
            ->get();


        $informAspectMedAmbient = DB::table('inform_aspect_med_ambient')
            ->select(
                'dispos_aguas_servid',
                'dispos_excrement_bovinos',
                'manejo_basuras',
                'manejo_empaq_produc_quimic'
            )
            ->where('id_predio', $id)
            ->get();


        $instalacionesEquipos = InstalacionesEquipos::with(['tipos_equipos'])
            ->where('id_predio', $id)
            ->get();



        $gestionInformacion = DB::table('gestion_informacion')
            ->select(
                'donde_regis_info_finca',
                'los_registros_son',
                'calcula_indicadores',
                'calcula_indicadores_de',
                'calcula_indicadores_de_para',
                'la_informacion_es',
                'utiliza_software_monitore',
                'utiliza_software_monitore_cual'
            )
            ->where('id_predio', $id)
            ->get();

        $razas = RazasGanado::all();

        return view('Predios.show', [
            'id_predio' => $id,
            'Predios' => $Predios,
            'Areas' => $Areas,
            'infoTierraAgua' => $infoTierraAgua,
            'manPastPotrCerc' => $manPastPotrCerc,
            'manGenGanado' => $manGenGanado,
            'informAspectMedAmbient' => $informAspectMedAmbient,
            'instalacionesEquipos' => $instalacionesEquipos,
            'gestionInformacion' => $gestionInformacion,
            'razas' => $razas
        ]);
    }
    public function edit($id): View
    {
        $Predios = Predios::findOrFail($id);

        $departamentos = DB::table('municipio')
            ->select(DB::raw('MIN(id) as id'), 'departamento')
            ->groupBy('departamento')
            ->get();

        $currentUser = Auth::user();

        if ($currentUser->role->name === 'admin') {
            // Administrador: todos los usuarios con rol 3
            $propietarios = User::where('id_rol', 3)->get();
        } elseif ($currentUser->role->name === 'propietario') {
            // Obtener los IDs de los predios asociados al usuario autenticado
            $prediosIds = $currentUser->predios->pluck('id')->toArray();

            if (empty($prediosIds)) {
                return redirect()->back()->withErrors('No tienes predios asociados para editar.');
            }

            // Grupo A: Usuarios con rol 3 que tengan predios compartidos o que hayan sido creados por el usuario actual.
            $groupA = User::where('id_rol', 3)
                ->where(function ($query) use ($prediosIds, $currentUser) {
                    $query->whereHas('predios', function ($subQuery) use ($prediosIds) {
                        $subQuery->whereIn('predios.id', $prediosIds);
                    })
                    ->orWhere('created_by', $currentUser->id);
                });

            // Grupo B: El usuario creador del usuario actual, si existe, y que tenga rol 3.
            if ($currentUser->created_by) {
                $groupB = User::where('id', $currentUser->created_by)
                    ->where('id_rol', 3);
                // Unión de ambas consultas
                $propietarios = $groupA->union($groupB)->get();
            } else {
                $propietarios = $groupA->get();
            }

            if ($propietarios->isEmpty()) {
                return redirect()->back()->withErrors('No se encontraron propietarios asociados.');
            }
        } else {
            // Otros roles sin permiso
            $propietarios = collect();
        }

        return view('Predios.edit', compact('Predios', 'departamentos', 'propietarios'));
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Predios $predio): RedirectResponse
    {
        // Reglas de validación actualizadas para recibir un arreglo de propietarios (puede ajustarse a un único valor si es necesario)
        $rules = [
            'id_usuario'   => ['nullable', 'array'],  // Se permite un arreglo de propietarios
            'id_usuario.*' => ['exists:users,id'],      // Cada valor debe existir en la tabla de usuarios
            'cod_predio'   => ['required', 'string', 'max:255', 'unique:predios,cod_predio,' . $predio->id],
            'departamento' => ['required', 'string', 'max:255'],
            'municipio'    => ['required', 'string', 'max:255'],
            'nombre_predio'=> ['nullable', 'string', 'max:255'],
            'vereda'       => ['nullable', 'string', 'max:255'],
            'forma_de_llegar' => ['nullable', 'string', 'max:255'],
            'latitud'      => ['nullable', 'numeric'],
            'longitud'     => ['nullable', 'numeric'],
        ];

        // Validar los datos del request
        $validatedData = $request->validate($rules);

        // Verificar permisos: el usuario debe ser propietario asignado, administrador o el creador del predio
        $currentUser = auth()->user();
        $propietarioAsignado = $predio->usuarios->where('id', $currentUser->id)->first();

        if (!($propietarioAsignado || $currentUser->role->name === 'admin' || $predio->created_by === $currentUser->id)) {
            return Redirect::route('predios.index')
                ->with('error', 'No tienes permiso para actualizar este predio.');
        }

        try {
            // Actualizar el predio con los datos validados
            $predio->update($validatedData);

            // Actualizar la relación en la tabla pivote: si se proporciona propietario(s), sincronizar; de lo contrario, remover relaciones existentes.
            if (isset($validatedData['id_usuario'])) {
                $predio->usuarios()->sync($validatedData['id_usuario']);
            } else {
                $predio->usuarios()->detach();
            }

            return redirect()->route('predios.index')
                ->with('success', 'Información del predio actualizada correctamente.');
        } catch (\Exception $e) {
            Log::error("Error al actualizar el predio con ID {$predio->id}: " . $e->getMessage());
            return back()->withErrors('Error al actualizar: ' . $e->getMessage());
        }
    }


    public function updatadeCaracterizacion(Request $request, $id)
    {

        // Sumar el total de medidas
        $cant_total = 0;
        foreach ($request->areas as $area) {
            $cant_total += $area['medidas'];
        }

        // Eliminar las áreas actuales relacionadas con el predio para actualizar
        Areas::where('id_predio', $id)->delete();

        // Crear o actualizar las áreas
        foreach ($request->areas as $area) {
            // Procesar la imagen si se ha subido una nueva
            $imagenPath = null;
            if (isset($area['imagen']) && $area['imagen'] instanceof \Illuminate\Http\UploadedFile) {
                $imagenPath = $this->moveImage($area['imagen']); // Llama a la función para mover la imagen
            }

            // Crear el registro del área con los datos actualizados, incluyendo la imagen si existe
            Areas::create([
                'id_tipo_area' => $area['id_tipo_area'],
                'id_predio' => $id,
                'medidas' => $area['medidas'],
                'materiales_establecidos' => $area['materiales_establecidos'],
                'cant_total' => $cant_total,
                'imagen' => $imagenPath, // Guardar la ruta de la imagen si está presente
            ]);
        }

        return redirect()->route('Seccion2', ['id' => $id])
            ->with('success', 'Caracterización del predio actualizada correctamente');
    }




    public function updateArea(Request $request, $id)
    {
        try {
            // Validar los datos de entrada
            $request->validate([
                'medidas' => 'required|numeric',
                'materiales_establecidos' => 'required|string',
            ]);

            // Buscar el área y actualizarla
            $area = Areas::findOrFail($id);
            $area->update([
                'medidas' => $request->medidas,
                'materiales_establecidos' => $request->materiales_establecidos,
            ]);

            // Redireccionar con un mensaje de éxito
            return redirect()->back()->with('success', 'Área actualizada correctamente.');
        } catch (\Exception $e) {
            // En caso de error, redireccionar con un mensaje de error
            return redirect()->back()->with('error', 'Error al actualizar el área: ' . $e->getMessage());
        }
    }

    public function updateinfoTierraAgua(Request $request, $id)
    {
        try {
            // Validación de todos los campos
            $request->validate([
                'suelos_predominantes' => 'nullable|string',
                'drenaje' => 'nullable|string',
                'manejo_cuencas_nac_agua' => 'nullable|string',
                'cantidad_preservacion' => 'nullable|integer',
                'porcentaje_preservacion' => 'nullable|integer',
                'fuente_calidad_agua' => 'nullable|string',
                'fuente_calidad_agua_uso_domestic' => 'nullable|string',
                'disp_agua_durant_veran_anim' => 'nullable|string',
                'disp_agua_durant_veran_anim_fuente' => 'nullable|string',
                'disp_agua_durant_veran_riesg' => 'nullable|string',
                'disp_agua_durant_veran_riesg_fuente' => 'nullable|string'
            ]);

            // Encuentra la instancia de InfoTierraAgua usando el ID proporcionado
            $infoTierraAgua = InfoTierraAgua::findOrFail($id);

            // Actualiza la instancia con los datos validados
            $infoTierraAgua->update($request->only([
                'suelos_predominantes',
                'drenaje',
                'manejo_cuencas_nac_agua',
                'cantidad_preservacion',
                'porcentaje_preservacion',
                'fuente_calidad_agua',
                'fuente_calidad_agua_uso_domestic',
                'disp_agua_durant_veran_anim',
                'disp_agua_durant_veran_anim_fuente',
                'disp_agua_durant_veran_riesg',
                'disp_agua_durant_veran_riesg_fuente'
            ]));

            // Redirige al usuario de vuelta a la página anterior con un mensaje de éxito
            return redirect()->back()->with('success', 'Información actualizada correctamente.');
        } catch (\Exception $e) {
            // Captura la excepción y redirige al usuario de vuelta con un mensaje de error
            return redirect()->back()->with('error', 'Error al actualizar la información: ' . $e->getMessage());
        }
    }

    public function updateManPastPotrCer(Request $request, $id)
    {
        try {
            // Validación de campos
            $validatedData = $request->validate([
                'area_dest_past' => 'required|string',
                'r_fertilazion_potreros' => 'required|string',
                'r_fertilazion_potreros_produc' => 'nullable|string',
                'r_fertilazion_potreros_cuant_año' => 'nullable|integer',
                'presen_plag_enferm' => 'nullable|string',
                'presen_plag_enferm_tipos' => 'nullable|string',
                'r_control_plagas' => 'nullable|string',
                'r_control_plagas_produc' => 'nullable|string',
                'r_control_plagas_cuant_año' => 'nullable|integer',
                'r_control_maleza' => 'nullable|string',
                'r_control_maleza_product' => 'nullable|string',
                'r_control_maleza_cuant_año' => 'nullable|integer',
                'precencia_heladas' => 'nullable|string',
                'precencia_heladas_intensidad' => 'nullable|string',
                'precencia_heladas_epocas' => 'nullable|string',
                'div_potreros' => 'nullable|string',
                'div_potreros_como' => 'nullable|string',
                'tipo_pastoreo' => 'nullable|string',
                'rotacional_dias_ocupacion' => 'nullable|integer',
                'rotacional_dias_descanso' => 'nullable|integer',
                'cercas' => 'nullable|string',
                'cercas_puas' => 'nullable|string',
                'cercas_electricas' => 'nullable|string',
                'la_produccion_forraje_suficiente_año' => 'nullable|string',
                'porque' => 'nullable|string',
            ]);

            // Encuentra la instancia de ManPastPotrCer
            $manPastPotrCer = ManPastPotrCer::findOrFail($id);

            // Actualiza la instancia
            $manPastPotrCer->update($validatedData);

            // Redirige con un mensaje de éxito
            return redirect()->back()->with('success', 'Información actualizada correctamente.');
        } catch (\Exception $e) {
            // Redirige con un mensaje de error si algo sale mal
            return redirect()->back()->with('error', 'Error en la actualización: ' . $e->getMessage());
        }
    }

    public function updateManGenGanado(Request $request, $id)
    {
        try {
            $request->validate([
                'id_raza_gan' => 'nullable|integer',
                'ident_animales' => 'nullable|string',
                'sistema_cria_ternero' => 'nullable|string',
                'aliment_ternero' => 'nullable|string',
                'sistem_levant_animal' => 'nullable|string',
                'manej_hembras_prox' => 'nullable|string',
                'manej_vacas_secas' => 'nullable|string',
                'tipo_ordeño' => 'nullable|string',
                'sistem_servic_reproduct' => 'nullable|string',
                'form_program_servicios' => 'nullable|string',
                'pesaje_animal' => 'nullable|string',
                'cuantos_animal_pesa' => 'nullable|string',
                'control_parasito_extern' => 'nullable|string',
                'control_parasito_extern_produc' => 'nullable|string',
                'control_parasito_extern_frecuenc' => 'nullable|string',
                'control_parasito_intern' => 'nullable|string',
                'control_parasito_intern_produc' => 'nullable|string',
                'control_parasito_intern_frecuenc' => 'nullable|string',
                'sumin_sal' => 'nullable|string',
                'a_sal_add_premezcla' => 'nullable|string',
                'a_sal_add_premezcla_especifique' => 'nullable|string',
                'como_manej_ganad_veran' => 'nullable|string',
                'como_manej_ganad_invier' => 'nullable|string',
                'r_pesaje_leche_hembr_lactantes' => 'nullable|string',
                'r_pesaje_leche_hembr_periodicidad' => 'nullable|string',
                'suplement_ganad_epoc_criti' => 'nullable|string',
                'suplement_ganad_epoc_criti_con_que' => 'nullable|string',
                'suplement_ganad_epoc_criti_que_lotes' => 'nullable|string',
            ]);

            $manGenGanado = ManGenGanado::findOrFail($id);
            $manGenGanado->update($request->all());

            return redirect()->back()->with('success', 'Datos del manejo general de ganado actualizados correctamente.');
        } catch (\Exception $e) {
            // Aquí puedes personalizar el manejo de errores, por ejemplo, registrando el error o enviando una notificación
            return redirect()->back()->with('error', 'Error al actualizar los datos: ' . $e->getMessage());
        }
    }

    public function updateInstalacionesEquipos(Request $request, $id_predio)
    {
        try {
            // Asegurarse de que el predio existe (opcional)
            $predio = Predios::findOrFail($id_predio);

            foreach ($request->equipos as $key => $equipo) {
                $instalacionEquipo = InstalacionesEquipos::where('id_predio', $id_predio)
                    ->where('id_tipos_equipos', $equipo['id_tipos_equipos'])
                    ->first();

                if ($instalacionEquipo) {
                    // Actualiza el registro existente
                    $instalacionEquipo->update([
                        'si' => $equipo['si'] ?? false,
                        'no' => $equipo['no'] ?? false,
                        'especificar' => $equipo['especificar'] ?? '',
                    ]);
                } else {
                    // Crea un nuevo registro si no existe
                    InstalacionesEquipos::create([
                        'id_predio' => $id_predio,
                        'id_tipos_equipos' => $equipo['id_tipos_equipos'],
                        'si' => $equipo['si'] ?? false,
                        'no' => $equipo['no'] ?? false,
                        'especificar' => $equipo['especificar'] ?? '',
                    ]);
                }
            }

            // Redirige hacia atrás con un mensaje de éxito
            return redirect()->back()->with('success', 'Instalaciones y equipos actualizados correctamente');
        } catch (\Exception $e) {
            // Redirige hacia atrás con detalles del error
            return redirect()->back()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }


    public function destroy($id)
    {
        $predio = Predios::findOrFail($id);
        $currentUser = auth()->user();

        // Solo administradores pueden eliminar
        if ($currentUser->role->name !== 'admin') {
            abort(403, 'No tienes permiso para eliminar este predio.');
        }

        $predio->delete();

        return redirect()->route('predios.index')
            ->with('success', 'Predio eliminado exitosamente.');
    }

    public function storeParametros(Request $request): RedirectResponse
    {
        // Validar los datos recibidos
        $data = $request->validate([
            'parametros' => 'required|array',
            'parametros.*.transicionHembra' => 'required|integer|min:1',
            'parametros.*.transicionMacho'   => 'required|integer|min:1',
            'parametros_dias_gestacion' => 'required|array',
            'parametros_dias_gestacion.*' => 'required|integer|min:1',
        ]);

        // Iterar sobre cada predio para actualizar sus parámetros
        foreach ($data['parametros'] as $predioId => $paramTransicion) {
            // Actualizar o crear configuración para transición de hembras:
           PredioParametro::updateOrCreate(
                [
                    'predio_id' => $predioId,
                    'estado_actual_id' => 4, // Hembra de levante
                ],
                [
                    'estado_nuevo_id' => 3, // Novilla de vientre
                    'dias_transicion' => $paramTransicion['transicionHembra'],
                ]
            );

            // Actualizar o crear configuración para transición de machos:
         PredioParametro::updateOrCreate(
                [
                    'predio_id' => $predioId,
                    'estado_actual_id' => 14, // Macho de levante
                ],
                [
                    'estado_nuevo_id' => 13, // Torete/macho de ceba
                    'dias_transicion' => $paramTransicion['transicionMacho'],
                ]
            );

            // Actualizar o crear el parámetro de días de gestación para el predio:
            \App\Models\ParametroDiasGestacion::updateOrCreate(
                [
                    'predio_id' => $predioId,
                ],
                [
                    'dias_gestacion' => $data['parametros_dias_gestacion'][$predioId],
                ]
            );
        }

        return redirect()->back()->with('success', 'Parámetros actualizados correctamente.');
    }

    /**
     * Devuelve una lista de predios para llamadas API (usado en selectores).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function listPrediosApi()
    {
        try {
            // Idealmente filtrar por usuario si es necesario, o mostrar todos si es para admin/selectores generales
            // Aquí asumimos que se devuelven todos los predios con los campos básicos
            $predios = Predios::select('id', 'nombre_predio', 'cod_predio') // Añadí cod_predio por si es útil
                            ->orderBy('nombre_predio')
                            ->get();

            return response()->json($predios); // Devuelve directamente el array de predios

        } catch (\Exception $e) {
            Log::error("Error al obtener lista de predios para API: " . $e->getMessage());
            // Devolver un array vacío o un error, según prefieras
            return response()->json(['error' => 'No se pudo obtener la lista de predios'], 500);
        }
    }
}