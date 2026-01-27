<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnimalesController;
use App\Http\Controllers\AnimalEstadoController;
use App\Http\Controllers\PartosController;
use App\Http\Controllers\UserController;
use App\Models\User;
use App\Models\Predios;
use App\Http\Controllers\VeterinarioController;
use App\Http\Controllers\PrediosController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\HistorialAnimalController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/* Llenado dinamico para ficha */
Route::get('/animal/{codigo}', [AnimalesController::class, 'getAnimalDetails']);

Route::prefix('/animales')->group(function () {
    
    // Buscar animales por código (identificacion_oficial), nombre o chip (identificacion_electronica)
    Route::get('/buscar', [HistorialAnimalController::class, 'buscarAnimales']);
    
    // Obtener historial completo de un animal
    Route::post('/historial', [HistorialAnimalController::class, 'obtenerHistorialCompleto']);
    
});

Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'apiSendResetLinkEmail']);
Route::post('/password/forgot', [ResetPasswordController::class, 'sendResetLinkEmail']);

/* Llenado dinamico para parto */
Route::get('/animal/parto/{id}', [AnimalesController::class, 'getAnimalDetailsParto']);

Route::get('/ubicacion/{codigo}', [AnimalesController::class, 'getUbicationAnimal']);
Route::get('animal/{animal_id}/estados', [AnimalEstadoController::class, 'getEstados']);
Route::get('animal/{animalId}/estados/{estadoProductivoId}/reproductivo', [AnimalesController::class, 'getProductivoStates']);

Route::get('/getUsers', [UserController::class, 'topThreeUsers']);

//Post
Route::get('/getVeterinarios/{userId}', [VeterinarioController::class, 'getVeterinarios'])->name('veterinarios.index');
Route::post('veterinarios/store', [VeterinarioController::class, 'store'])->name('veterinarios.store');


Route::post('/check-user', function (Request $request) {
    // Validar que se envíen email y celular
    $request->validate([
        'email'   => 'required|email',
        'celular' => 'required',
    ]);

    // Buscar si existe algún usuario con el email o celular
    $exists = User::where('email', $request->email)
        ->orWhere('celular', $request->celular)
        ->exists();
    return response()->json(['exists' => $exists]);
});

// Ruta para obtener los predios del usuario autenticado
Route::middleware('auth:sanctum')->get('/user/predios', function (Request $request) {
    // Obtener el usuario autenticado
    $user = $request->user();

    // Aquí debes adaptar esto según tu modelo de datos - esta es una implementación básica
    // Asumiendo que hay una relación entre Usuario y Predios
    if (method_exists($user, 'predios')) {
        $predios = $user->predios()->get();
    } else {
        // Si no hay relación directa, buscar los predios asignados al usuario de otra manera
        // Por ejemplo, si hay un campo user_id en la tabla predios
        $predios = Predios::where('user_id', $user->id)->get();

        // O si es a través de una tabla intermedia
        // $predios = Predios::whereHas('usuarios', function($q) use ($user) {
        //     $q->where('users.id', $user->id);
        // })->get();
    }

    return response()->json([
        'success' => true,
        'predios' => $predios
    ]);
});

// Ruta para obtener detalles de un predio específico (para caché offline)
Route::middleware('auth:sanctum')->get('/predio/{id}/details', function (Request $request, $id) {
    // Aquí puedes obtener los detalles del predio que necesitan estar disponibles offline
    $predio = Predios::findOrFail($id);

    // Verificar que el usuario tenga acceso a este predio
    $user = $request->user();

    // Aquí debes implementar la lógica para verificar que el usuario tenga acceso al predio
    // Esto depende de tu modelo de datos y reglas de negocio

    // Ejemplo: Si hay una relación directa
    // $hasAccess = $user->predios()->where('predios.id', $id)->exists();

    // Por ahora asumimos que tienen acceso
    $hasAccess = true;

    if (!$hasAccess) {
        return response()->json([
            'success' => false,
            'message' => 'No tienes acceso a este predio'
        ], 403);
    }

    // Obtener datos adicionales que necesites para la edición offline
    // Por ejemplo, propietarios, tipos de predios, etc.
    $data = [
        'predio' => $predio,
        // Agrega aquí otros datos necesarios para la edición
        // 'propietarios' => \App\Models\Propietario::all(),
        // 'tipos_predio' => \App\Models\TipoPredio::all(),
    ];

    return response()->json([
        'success' => true,
        'data' => $data
    ]);
});

// Ruta para obtener la lista de predios para selectores
Route::get('/predios/list', [PrediosController::class, 'listPrediosApi'])->name('api.predios.list');

