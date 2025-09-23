<?php

namespace App\Http\Controllers;

use App\Models\Predios;
use App\Models\User;
use App\Models\Lote;
use App\Models\Potrero;
use App\Models\Animal;
use App\Models\RazasGanado;
use App\Models\PartoAnimal;
use App\Models\UserMembership;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Models\MembershipPlan;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;



class UserController extends Controller
{

    public function index(Request $request)
    {
        $currentUser =  Auth::user();
        $roleName = $currentUser->role->name;
        // Query inicial para obtener los usuarios
        $query = User::query();

        if ($roleName === 'admin' || $currentUser->role_id === 1) {
            // Admin o role_id=1: Pueden ver todos los usuarios
            $users = $query->get();
        } elseif ($roleName === 'propietario') {
            // Propietario: Ver usuarios que comparten al menos un predio o que creó el usuario autenticado
            $userPredioIds = $currentUser->predios->pluck('id'); // IDs de los predios asociados al usuario actual

            $users = $query->where(function ($q) use ($userPredioIds, $currentUser) {
                $q->whereHas('predios', function ($subQuery) use ($userPredioIds) {
                    $subQuery->whereIn('id_predio', $userPredioIds); // Usuarios que comparten predios
                })->orWhere('created_by', $currentUser->id); // Usuarios creados por el usuario autenticado
            })->get();
        } else {
            // Otros roles (como encuestador): Solo pueden ver su propio usuario
            $users = $query->where('id', $currentUser->id)->get();
        }

        // Obtener predios, lotes, potreros y otros recursos relacionados al usuario
        $predios = $currentUser->predios;
        $lotes = Lote::whereIn('predio_id', $predios->pluck('id'))->get();
        $potreros = Potrero::whereIn('predio_id', $predios->pluck('id'))->get();
        $animales = Animal::whereIn('id_predio', $predios->pluck('id'))
            ->with([
                'movimientos',
                'movimientos.lote',
                'movimientos.potrero',
                'movimientos.predio',
                'ultimoParto'
            ])
            ->get();
        $razas = RazasGanado::all();
        $partos = PartoAnimal::whereIn('id_animal', $animales->pluck('id_animal'))->get();
        $estadosReproductivos = []; // Puedes poblarlo si lo necesitas
        $estadosProductivos = [];  // Puedes poblarlo si lo necesitas
        $roles = Role::all();

        return view('user.index', compact(
            'animales',
            'partos',
            'estadosReproductivos',
            'estadosProductivos',
            'predios',
            'lotes',
            'razas',
            'users',
            'potreros',
            'roles'
        ));
    }

    public function create(): View
    {
        $user = new User();
        $roles = Role::all();
        return view('user.create', compact('user', 'roles'));
    }
    public function welcome()
    {
        return view('welcome');
    }

    public function store(UserRequest $request)
    {
        try {
            DB::beginTransaction();

            // Validar los datos del formulario (UserRequest se encarga de devolver 422 en AJAX si falla)
            $data = $request->validated();

            // Encriptar la contraseña
            $data['password'] = Hash::make($data['password']);

            // Subir la foto de perfil si existe
            if ($request->hasFile('profile_photo')) {
                $filename = uniqid() . '_' . time() . '.' . $request->profile_photo->extension();
                $path = $request->file('profile_photo')->storeAs('profile_photos', $filename, 'public');
                $data['profile_photo_path'] = $path;
            }

            // Asignar el ID del usuario autenticado al campo created_by
            $data['created_by'] = Auth::id();

            // Crear el usuario
            $user = User::create($data);

            // Asociar predios si fueron seleccionados
            if ($request->filled('predios')) {
                $user->predios()->attach($request->input('predios'));
            }

            DB::commit();

             // Cargar relaciones necesarias antes de devolver la respuesta JSON
            $user->load('role', 'predios');

            // Devolver respuesta JSON exitosa
            return response()->json($user, 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Laravel maneja esto automáticamente para AJAX devolviendo 422
            // No es necesario hacer nada aquí si se usa UserRequest o $request->validate()
            // Pero si quisiéramos devolverlo manualmente:
             // return response()->json(['message' => 'Error de validación', 'errors' => $e->errors()], 422);
            // Por si acaso, relanzamos para que Laravel lo maneje
             throw $e;
        } catch (\Exception $e) {
            DB::rollBack();

            // Registrar el error
            Log::error('Error al crear usuario: ' . $e->getMessage());

            // Devolver respuesta JSON de error genérico
            return response()->json([
                'message' => 'Hubo un problema al crear el usuario. Por favor, inténtalo de nuevo.',
                 'error' => $e->getMessage() // Opcional: enviar detalles del error (cuidado en producción)
            ], 500);
        }
    }

    public function show($id): View
    {
        $user = User::find($id);
        $roles = Role::all();
        return view('user.show', compact('user'));
    }

    public function edit($id): View
    {
        $user = User::find($id);
        $roles = Role::all();
        return view('user.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        try {
          /*   dd($request->all()); */
            // Validar los datos proporcionados
            $validatedData = $request->validate([
                'name' => 'nullable|string|max:255',
                'email' => 'nullable|string|email|max:255|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:8',
                'id_rol' => 'nullable|string|exists:roles,id',
                'estado' => 'nullable|string|in:Activo,expirado', // Validar el campo de estado
                'regimen' => 'nullable|string|in:comun,simplificado', // <-- Añadir validación para regimen
            ]);

            // Verificar si la contraseña está presente y no vacía
            if (!empty($validatedData['password'])) {
                $validatedData['password'] = Hash::make($validatedData['password']);
            } else {
                unset($validatedData['password']); // Si no se proporciona la contraseña, no se actualiza
            }

            // Actualizar al usuario con los datos validados, incluido el estado
            $user->update($validatedData);

            // Redirigir con éxito si todo sale bien
            return Redirect::back()
                ->with('success', 'Usuario actualizado correctamente');
        } catch (\Exception $e) {
            // Manejar cualquier excepción que ocurra y redirigir con un mensaje de error
            return Redirect::back()
                ->withInput()
                ->with('error', 'Hubo un problema al actualizar el usuario. Por favor, inténtalo de nuevo.');
        }
    }

    public function destroy($id): RedirectResponse
    {
        try {
            // Iniciar una transacción para asegurar la integridad de los datos
            DB::beginTransaction();

            // Buscar al usuario por su ID
            $user = User::find($id);

            // Verificar si el usuario existe
            if (!$user) {
                return Redirect::route('users.index')
                    ->with('error', 'Usuario no encontrado.');
            }

            // Eliminar la foto de perfil si existe
            if ($user->profile_photo_path) {
                // Verificar si el archivo existe en el disco 'public'
                if (Storage::disk('public')->exists($user->profile_photo_path)) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                } else {
                    Log::warning("La foto de perfil del usuario ID {$id} no se encontró en el disco 'public'.");
                }
            }
            $user->predios()->detach();
            // Eliminar al usuario
            $user->delete();
            // Confirmar la transacción
            DB::commit();
            // Redirigir con mensaje de éxito
            return Redirect::route('users.index')
                ->with('success', 'Usuario eliminado exitosamente.');
        } catch (\Exception $e) {
            // Deshacer la transacción en caso de error
            DB::rollBack();

            // Registrar el error para fines de depuración
            Log::error('Error al eliminar usuario: ' . $e->getMessage());

            // Redirigir de vuelta con mensaje de error
            return Redirect::route('users.index')
                ->with('error', 'Hubo un problema al eliminar el usuario. Por favor, inténtalo de nuevo.');
        }
    }
    public function topThreeUsers()
    {
        // Obtener los primeros 3 usuarios
        $users = User::orderBy('created_at', 'asc')->take(3)->get();

        // Devolver en formato JSON
        return response()->json($users);
    }



/* Membresias */

public function asignarMembresia(Request $request)
{
    // Validar los datos del formulario
    $data = $request->validate([
        'user_id'            => 'required|exists:users,id',
        'membership_plan_id' => 'required|exists:membership_plans,id',
        'fecha_inicio'       => 'required|date',
        'fecha_expiracion'   => 'required|date|after:fecha_inicio',
    ]);

    // Definir el estado como "activo" y el flag de free trial según corresponda
    $data['estado'] = 'activo';
    $data['es_free_trial'] = false;

    // Desactivar las membresías activas existentes para ese usuario
    UserMembership::where('user_id', $data['user_id'])
        ->where('estado', 'activo')
        ->update(['estado' => 'expirado']);

    // Crear la nueva membresía con estado "activo"
    $membership = UserMembership::create([
        'user_id'            => $data['user_id'],
        'membership_plan_id' => $data['membership_plan_id'],
        'fecha_inicio'       => $data['fecha_inicio'],
        'fecha_expiracion'   => $data['fecha_expiracion'],
        'estado'             => $data['estado'],
        'es_free_trial'      => $data['es_free_trial'],
    ]);

    return redirect()->back()->with('success', 'Membresía asignada correctamente.');
}

public function actualizarMembresia(Request $request, UserMembership $membership)
{
    $request->validate([
        'estado'           => 'required|in:activo,pendiente,rechazado,expirado',
        'fecha_expiracion' => 'required|date|after_or_equal:' . $membership->fecha_inicio,
    ]);
    try {
        // Si se actualiza la membresía a "activo", desactivar las demás del mismo usuario
        if ($request->estado === 'activo') {
            UserMembership::where('user_id', $membership->user_id)
                ->where('estado', 'activo')
                ->where('id', '!=', $membership->id)
                ->update(['estado' => 'expirado']);
        }

        $membership->update([
            'estado'           => $request->estado,
            'fecha_expiracion' => $request->fecha_expiracion,
        ]);

        return redirect()->back()->with('success', 'Membresía actualizada exitosamente.');
    } catch (\Exception $e) {
        Log::error('Error al actualizar la membresía: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Ocurrió un error al actualizar la membresía.');
    }
}



    public function solicitarMembresia(Request $request)
    {
        $request->validate([
            'membership_plan_id' => 'required|exists:membership_plans,id',
        ]);

        $user = Auth::user();
        $plan = MembershipPlan::findOrFail($request->membership_plan_id);

        $fechaInicio = now();
        $fechaExpiracion = $fechaInicio->copy()->addDays($plan->duracion_dias);

        UserMembership::create([
            'user_id'            => $user->id,
            'membership_plan_id' => $plan->id,
            'fecha_inicio'       => $fechaInicio,
            'fecha_expiracion'   => $fechaExpiracion,
            'estado'             => 'pendiente',
            'es_free_trial'      => false,
        ]);

        return redirect()->back()->with('success', 'Solicitud de membresía enviada correctamente.');
    }


    public function administrarMembresias()
    {
        $user = Auth::user();
        // Obtener las membresías agrupadas por usuario, tomando la más reciente de cada uno
        $memberships = UserMembership::with(['user', 'membershipPlan'])
            ->whereIn('estado', ['pendiente', 'activo', 'rechazado'])
            ->orderByRaw('CASE
                WHEN estado = "pendiente" THEN 1
                WHEN estado = "activo" THEN 2
                WHEN estado = "rechazado" THEN 3
                ELSE 4 END')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('user_id')
            ->map(function ($group) {
                return $group->first();
            })
            ->sortByDesc(function ($membership) {
                if ($membership->estado === 'pendiente') return 3;
                if ($membership->estado === 'activo') return 2;
                return 1;
            })
            ->values();

        // Obtener las solicitudes pendientes
        $solicitudes = UserMembership::with(['user', 'membershipPlan'])
            ->where('estado', 'pendiente')
            ->orderBy('created_at', 'desc')
            ->get();

        $planes = MembershipPlan::all();

        return view('inicio.membresias', compact('user', 'memberships', 'solicitudes', 'planes'));
    }



    public function mostrarMembresias()
    {
        $user = Auth::user();

        if ($user->role->name === 'admin') {
            // Para administradores: se cargan todas las solicitudes y membresías
            $solicitudes = UserMembership::with('user', 'membershipPlan')
                ->where('estado', 'pendiente')
                ->orderBy('created_at', 'desc')
                ->get();
            $memberships = UserMembership::with('user',  'membershipPlan')->get();
            $usuarios = User::all();
            $planes = MembershipPlan::all();

            return view('inicio.membresias', compact('user', 'planes', 'usuarios', 'memberships', 'solicitudes'));
        } elseif ($user->role->name === 'propietario') {
            // Para propietarios: se obtiene su membresía actual y los planes disponibles
            $membership = $user->membership; // Relación definida en el modelo User
            $planes = MembershipPlan::all();
            $memberships = UserMembership::with('user', 'membershipPlan')->where('user_id', $user->id)->get();
            $usuarios = User::all();
            return view('inicio.membresias', compact('user', 'memberships', 'membership', 'planes', 'usuarios'));
        }
    }



public function ajustes()
{
    $user = Auth::user();
    // Carga los predios con sus parámetros
    $predios = $user->predios()->with('parametros')->get();
    return view('inicio.ajustes', compact('user', 'predios'));
}

// Nuevo método para obtener datos del usuario para el modal de edición
public function getData($id)
{
    $user = User::with('role', 'predios')->find($id);

    if (!$user) {
        return response()->json(['message' => 'Usuario no encontrado.'], 404);
    }

    // Puedes añadir lógica de autorización aquí si es necesario
    // (ej. verificar si el usuario autenticado puede ver/editar este usuario)

    return response()->json($user);
}

// Método original update, ahora adaptado para AJAX y renombrado (o puedes reemplazar el original)
public function updateAjax(Request $request, $id) //: RedirectResponse -> Ya no redirecciona, devuelve JSON
{
    $user = User::find($id);
    if (!$user) {
        return response()->json(['message' => 'Usuario no encontrado.'], 404);
    }

    // Lógica de autorización (ej: solo admin o el propio usuario pueden editar?)
    // if (Auth::user()->cannot('update', $user)) {
    //     return response()->json(['message' => 'No autorizado.'], 403);
    // }

    try {
        // Validar los datos proporcionados
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed', // 'confirmed' valida contra password_confirmation
            'id_rol' => 'required|string|exists:roles,id',
            'estado' => 'required|string|in:activo,expirado',
            'tipo_documento' => 'required|string|in:nit,cedula,cedula_extranjeria,pasaporte',
            'documento' => 'required|string|max:20',
            'predios' => 'nullable|array', // Aceptar un array de IDs de predios
            'predios.*' => 'exists:predios,id',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validación para la foto
            'remove_profile_photo' => 'nullable|boolean' // Para saber si quitar la foto
        ]);

        DB::beginTransaction();

        // Actualizar campos básicos
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->id_rol = $validatedData['id_rol'];
        $user->estado = $validatedData['estado'];
        $user->tipo_documento = $validatedData['tipo_documento'];
        $user->documento = $validatedData['documento'];

        // Actualizar la contraseña solo si se proporcionó una nueva
        if (!empty($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        // Manejar la foto de perfil
        if ($request->input('remove_profile_photo') == '1' && $user->profile_photo_path) {
            // Eliminar foto existente
            if (Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $user->profile_photo_path = null;
        } elseif ($request->hasFile('profile_photo')) {
             // Eliminar foto anterior si existe una nueva
             if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
             }
            // Subir nueva foto
            $filename = uniqid() . '_' . time() . '.' . $request->profile_photo->extension();
            $path = $request->file('profile_photo')->storeAs('profile_photos', $filename, 'public');
            $user->profile_photo_path = $path;
        }
        // Si no se marca quitar y no se sube nueva, no se toca la foto existente

        // Guardar cambios básicos y de foto/contraseña
        $user->save();

        // Sincronizar predios (reemplaza las asociaciones existentes con las nuevas)
        $user->predios()->sync($request->input('predios', []));

        DB::commit();

        // Cargar relaciones actualizadas antes de devolver
        $user->load('role', 'predios');

        // Devolver respuesta JSON exitosa
        return response()->json($user, 200);

    } catch (\Illuminate\Validation\ValidationException $e) {
        // Laravel devuelve 422 automáticamente para AJAX
         throw $e;
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al actualizar usuario (AJAX) ID ' . $id . ': ' . $e->getMessage());
        return response()->json([
            'message' => 'Hubo un problema al actualizar el usuario. Por favor, inténtalo de nuevo.',
             'error' => $e->getMessage() // Opcional: enviar detalles del error
        ], 500);
    }
}

}
