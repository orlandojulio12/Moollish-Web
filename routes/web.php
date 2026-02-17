<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\CarazterizacionRiesgoController;
use App\Http\Controllers\GestionInformacionController;
use App\Http\Controllers\InforEpidemiologicaController;
use App\Http\Controllers\InformacionBgpController;
use App\Http\Controllers\InformAspectMedAmbientController;
use App\Http\Controllers\InfoTierraAguaController;
use App\Http\Controllers\InstalacionesEquiposController;
use App\Http\Controllers\ManGenGanadoController;
use App\Http\Controllers\ManPastPotrCerController;
use App\Http\Controllers\PalpacionesController;
use App\Http\Controllers\PrediosController;
use App\Http\Controllers\PropietarioController;
use App\Http\Controllers\PropietariosController;
use App\Http\Controllers\RazasGanadoController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiciosAmbientalesController;
use App\Http\Controllers\TipoExplotacionController;
use App\Http\Controllers\TiposInstalacionesEquipoController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VeterinarioController;
use App\Http\Controllers\VisitaPrediosRiesgoController;
use App\Http\Controllers\CensoBovinoController;
use App\Http\Controllers\CensoPorcinoController;
use App\Http\Controllers\CensoEquidoController;
use App\Http\Controllers\CensoBufalinoController;
use App\Http\Controllers\CensoOtrasEspecController;
use App\Http\Controllers\CensoOvinoCaprinoController;
use App\Http\Controllers\CensoPezController;
use App\Http\Controllers\CensoCructaceoController;
use App\Http\Controllers\CensoAvesComercialesController;
use App\Http\Controllers\CensoAvesTraspatioController;
use App\Http\Controllers\CensoAbejasController;
use App\Http\Controllers\IdentificacionAnimalController;
use App\Http\Controllers\GastoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AsignPrediosEncuestadorController;
use App\Http\Controllers\exportePredioController;
use App\Http\Controllers\animalesIndenticacionController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\AnimalesController;
use App\Http\Controllers\UbicacionController;
use App\Http\Controllers\PesajeAnimalController;
use App\Http\Controllers\AnimalEstadoController;
use App\Http\Controllers\PartosController;
use App\Http\Controllers\PesajeLecheController;
use App\Http\Controllers\SecadoDesteteController;
use App\Http\Controllers\MontaNaturalController;
use App\Http\Controllers\MuerteAnimalController;
use App\Http\Controllers\VentaAnimalController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\InsumosController;
use App\Http\Controllers\AplicacionInsumosController;
use App\Http\Controllers\InsumoController;
use App\Http\Controllers\MasterController;
use App\Models\Predios;
use Illuminate\Http\Request;
use App\Http\Controllers\ProductivosController;
use App\Http\Controllers\ReproductivosController;




Route::get('/', function () {
    return view('welcome');
});
Route::view('/contactanos', 'contactanos')->name('contactanos');

Route::get('/', 'App\Http\Controllers\UserController@welcome')->name('/');
Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::post('/register', [RegisterController::class, 'register'])->name('register.user');
// Ruta para verificación de correo electrónico
Route::get('/verify-email/{token}', [RegisterController::class, 'verifyEmail'])
    ->name('verification.verify');

// Ruta para reenviar correo de verificación
Route::post('/resend-verification-email', [RegisterController::class, 'resendVerificationEmail'])
    ->name('verification.resend');

// Ruta para la página de confirmación de correo electrónico
Route::get('/email-confirmation', function() {
    return view('inicio.confirmEmail');
})->name('email.confirmation');

// Rutas para restablecer contraseña
Route::get('/forgot-password', function () {
    return view('auth.login');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password/sendmail', [App\Http\Controllers\Auth\ResetPasswordController::class, 'sendResetLinkEmail'])
    ->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])
    ->middleware('guest')->name('password.reset');

Route::post('/reset-password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])
    ->middleware('guest')->name('password.update');

Route::middleware([
    'auth:sanctum',
    '\App\Http\Middleware\PreventBackHistory::class',
    config('jetstream.auth_session'),
    'verified',
      'active.membership'
])->group(function () {

    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('areas', AreaController::class);
    // Rutas para las vistas de áreas por tipo de medida
    Route::get('/hectareas', [AreaController::class, 'hectareas'])->name('areas.hectareas');
    Route::get('/metros-cuadrados', [AreaController::class, 'metrosCuadrados'])->name('areas.metros_cuadrados');
    // Rutas para ver los detalles de hectáreas y metros cuadrados
    Route::get('/areas/detalle/hectareas/{id}', [AreaController::class, 'detalleHectareas'])->name('areas.detalle.hectareas');
    Route::get('/areas/detalle/metros/{id}', [AreaController::class, 'detalleMetrosCuadrados'])->name('areas.detalle.metros');

    Route::get('/predios/grafica', [PrediosController::class, 'mostrarGrafica'])->name('predios.grafica');


    Route::resource('propietarios', PropietarioController::class);
    Route::resource('razas-ganados', RazasGanadoController::class);
    Route::resource('tipos-instalaciones-equipos', TiposInstalacionesEquipoController::class);
    Route::resource('predios', PrediosController::class);

    Route::get('/grafica-predios-propietario', [PropietarioController::class, 'mostrarGraficaPrediosPorPropietario'])->name('grafico.prepietario');

    Route::get('/animalesIndenticacion', [animalesIndenticacionController::class, 'show'])->name('animalesIndenticacion');
    Route::get('/get-municipios/{departamento}', [PrediosController::class, 'getMunicipiosByDepartamento'])->name('getMunicipiosByDepartamento');
    Route::get('predios/{id}/caracterizar', [PrediosController::class, 'caracterizar'])->name('predios.caracterizar');
    Route::post('predios/{id}/caracterizar', [PrediosController::class, 'storeCaracterizacion'])->name('predios.storeCaracterizacion');
    Route::put('predios/{id}/actualizar-caracterizacion', [PrediosController::class, 'updatadeCaracterizacion'])->name('predios.updatadeCaracterizacion');
    
    //exporte de Excel predios
    // Route::get('ExportePredio', [exportePredioController::class, 'show'])->name('ExportePredio');
    // Route::get('/export-informacion-del-predio', [exportePredioController::class, 'exportarInformacionDelPredio'])->name('exportarInformacionDelPredio');
    // Route::get('/export-Bgp', [exportePredioController::class, 'exportBgp'])->name('exportBgp');
    // Route::get('/export-riesgo-epidemiologico', [exportePredioController::class, 'exportRiesgoEpidemiologico'])->name('export.riesgo.epidemiologico');
    // Route::get('export/servicios-ambientales', [exportePredioController::class, 'exportServiciosAmbientales'])->name('exportServiciosAmbientales');
    // Route::get('export/censo', [exportePredioController::class, 'exportCenso'])->name('exportCenso');
    
    // Agrupar las rutas de exportación con middleware para solo admin (rol 1)
 Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('ExportePredio', [exportePredioController::class, 'show'])->name('ExportePredio');
    Route::get('/buscar-predios', [exportePredioController::class, 'buscarPredios'])->name('buscarPredios'); 
   Route::get('/export-informacion-del-predio', [exportePredioController::class, 'exportarInformacionDelPredio'])->name('exportarInformacionDelPredio');
   Route::get('/export-Bgp', [exportePredioController::class, 'exportBgp'])->name('exportBgp');
    Route::get('/export-riesgo-epidemiologico', [exportePredioController::class, 'exportRiesgoEpidemiologico'])->name('export.riesgo.epidemiologico');
    Route::get('export/servicios-ambientales', [exportePredioController::class, 'exportServiciosAmbientales'])->name('exportServiciosAmbientales');
    Route::get('export/censo', [exportePredioController::class, 'exportCenso'])->name('exportCenso');
 });
    
    Route::get('Seccion2/{id}', [PrediosController::class, 'showSeccion2'])->name('Seccion2');

    //asignacion de predios
    Route::get('/asignaciones', [AsignPrediosEncuestadorController::class, 'show'])->name('asignaciones.show');
    Route::post('/asignaciones', [AsignPrediosEncuestadorController::class, 'store'])->name('asignaciones.store');
    Route::put('/asignaciones/{id}', [AsignPrediosEncuestadorController::class, 'update'])->name('asignaciones.update');
    Route::delete('/asignaciones/{id}', [AsignPrediosEncuestadorController::class, 'destroy'])->name('asignaciones.destroy');


    // Rutas de Informacion de Tierra y agua
    Route::get('info_tierra_agua/create/{id}', [InfoTierraAguaController::class, 'create'])->name('info_tierra_agua.create');
    Route::put('info_tierra_agua/{id}/actualizarCaracterizacion', [InfoTierraAguaController::class, 'updatadeCaracterizacion'])->name('info_tierra_agua.updatadeCaracterizacion');

    Route::post('info_tierra_agua', [InfoTierraAguaController::class, 'store'])->name('info_tierra_agua.store');
    // Rutas para manejo,Pastoreo y cercas
    Route::get('man_past_potr_cerc/create/{id}', [ManPastPotrCerController::class, 'create'])->name('man_past_potr_cerc.create');
    Route::put('/man-past-potr-cerc/{id}', [ManPastPotrCerController::class, 'update'])->name('man_past_potr_cerc.update');
    Route::post('man_past_potr_cerc/store', [ManPastPotrCerController::class, 'store'])->name('man_past_potr_cerc.store');
    // Ruta para manejor general de Ganado
    Route::get('/man_gen_ganado/create/{id}', [ManGenGanadoController::class, 'create'])->name('man_gen_ganado.create');
    Route::put('/man_gen_ganado/update/{id}', [ManGenGanadoController::class, 'update'])->name('man_gen_ganado.update');
    Route::post('/man_gen_ganado', [ManGenGanadoController::class, 'store'])->name('man_gen_ganado.store');
    //rutas para informacion general de medio ambientes
    Route::get('/info_aspect_med_ambient/create/{id}', [InformAspectMedAmbientController::class, 'create'])->name('info_aspect_med_ambient.create');
    Route::put('info_aspect_med_ambient/update/{id}', [InformAspectMedAmbientController::class, 'update'])->name('info_aspect_med_ambient.update');
    Route::post('/info_aspect_med_ambient', [InformAspectMedAmbientController::class, 'store'])->name('info_aspect_med_ambient.store');
    //rutas para instalaciones de equipos
    Route::get('instalaciones_equipos/create/{id}', [InstalacionesEquiposController::class, 'create'])->name('instalaciones_equipos.create');
    Route::put('instalaciones_equipos/update/{id}', [InstalacionesEquiposController::class, 'update'])->name('instalaciones_equipos2.update');
    Route::post('instalaciones_equipos/store', [InstalacionesEquiposController::class, 'store'])->name('instalaciones_equipos.store');
    // rutas para gestion de informacion
    Route::get('/gestion_informacion/create/{id_predio}', [GestionInformacionController::class, 'create'])->name('gestion_informacion.create');
    Route::post('/gestion_informacion/store/{id}', [GestionInformacionController::class, 'store'])->name('gestion_informacion.store');
    Route::put('/gestion_informacion/update/{id}', [GestionInformacionController::class, 'update'])->name('gestion_informacion.update');

    // rutas seccion 3
    Route::get('Seccion3/{id}', [PrediosController::class, 'showSeccion3'])->name('Seccion3');
    Route::get('informacion-bgps/create/{id}', [InformacionBgpController::class, 'create'])->name('informacion-bgps.create');
    Route::post('informacion-bgps/store', [InformacionBgpController::class, 'store'])->name('informacion-bgps.store');
    Route::get('informacion-bgps/show/{id}', [InformacionBgpController::class, 'show'])->name('informacion-bgps.show');
    Route::put('/informacion-bgp/{id}', [InformacionBgpController::class, 'updateInformacionBgp'])->name('informacionBgp.update');
    // rutas secion 4
    Route::get('Seccion4/{id}', [PrediosController::class, 'showSeccion4'])->name('Seccion4');
    Route::get('/tipo-de-explotacion/{id}', [TipoExplotacionController::class, 'create'])->name('tipo_explotacion.create');
    Route::put('/tipo-de-explotacion/{id}', [TipoExplotacionController::class, 'update'])->name('tipo_explotacion.update');
    Route::post('tipo-de-explotacion/store', [TipoExplotacionController::class, 'store'])->name('tipo_explotacion.store');
    Route::put('predios/{id}/update-area', [PrediosController::class, 'updateArea'])->name('predios.updateArea');
    Route::patch('infoTierraAgua/{id}/update', [PrediosController::class, 'updateinfoTierraAgua'])->name('infoTierraAgua.update');
    Route::get('/Secciones/{id}', [PrediosController::class, 'showSecciones'])->name('Secciones');
    Route::patch('man-past-potr-cerc/{id}/update', [PrediosController::class, 'updateManPastPotrCer'])->name('manPastPotrCer.update');
    Route::patch('manGenGanado/{id}/update', [PrediosController::class, 'updateManGenGanado'])->name('manGenGanado.update');
    // Ruta para actualizar instalaciones y equipos de un predio específico
    Route::put('/instalaciones_equipos/{id_predio}', [PrediosController::class, 'updateInstalacionesEquipos'])->name('instalaciones_equipos.update');

    Route::get('/informacion-epidemiologica/{id_predio}', [InforEpidemiologicaController::class, 'create'])->name('inforEpidemiologica.create');
    Route::post('informacion-epidemiologica/store', [InforEpidemiologicaController::class, 'store'])->name('inforEpidemiologica.store');
    Route::put('informacion-epidemiologica/update/{id}', [InforEpidemiologicaController::class, 'update'])->name('inforEpidemiologica.update');

    Route::get('caracterizacion_riesgo/{id_predio}', [CarazterizacionRiesgoController::class, 'create'])->name('caracterizacion_riesgo.create');
    Route::put('caracterizacion_riesgo/update/{id}', [CarazterizacionRiesgoController::class, 'update'])->name('caracterizacion_riesgo.update');
    Route::post('caracterizacion_riesgo/store', [CarazterizacionRiesgoController::class, 'store'])->name('caracterizacion_riesgo.store');

    Route::get('/visita_predios_riesgo/{id_predio}', [VisitaPrediosRiesgoController::class, 'create'])->name('visita_predios_riesgo.create');
    Route::put('/visita_predios_riesgo/update/{id}', [VisitaPrediosRiesgoController::class, 'update'])->name('visita_predios_riesgo.update');
    Route::post('/visita_predios_riesgo/store', [VisitaPrediosRiesgoController::class, 'store'])->name('visita_predios_riesgo.store');

    // Rutas seccion 5
    Route::get('servicios-ambientales/{id}', [ServiciosAmbientalesController::class, 'create'])->name('servicios_ambientales.create');
    Route::put('servicios-ambientales/{id_predio}', [ServiciosAmbientalesController::class, 'update'])->name('servicios_ambientales.update');
    Route::post('servicios-ambientales/store', [ServiciosAmbientalesController::class, 'store'])->name('servicios_ambientales.store');

    //rusta sección 6
    Route::get('Seccion6/{id}', [PrediosController::class, 'showSeccion6'])->name('Seccion6');
    Route::get('CensoBovino/{id}', [CensoBovinoController::class, 'show'])->name('CensoBovino');
    Route::post('CensoBovino/store', [CensoBovinoController::class, 'store'])->name('censo.store');
    Route::put('censo_bovino/update/{id}', [CensoBovinoController::class, 'update'])->name('censo.update');

    Route::get('CensoBufalino/{id}', [CensoBufalinoController::class, 'show'])->name('CensoBufalino');
    Route::post('CensoBufalino', [CensoBufalinoController::class, 'store'])->name('censo_bufalinos.store');
    Route::put('CensoBufalino/{id}', [CensoBufalinoController::class, 'update'])->name('censo_bufalinos.update');


    Route::post('CensoPorcino', [CensoPorcinoController::class, 'store'])->name('censo_porcinos.store');
    Route::get('CensoPorcino/{id}', [CensoPorcinoController::class, 'show'])->name('censo_porcinos');
    Route::put('CensoPorcino/{id}', [CensoPorcinoController::class, 'update'])->name('censo_porcinos.update');


    Route::post('CensoEquido', [CensoEquidoController::class, 'store'])->name('censo_equidos.store');
    Route::get('CensoEquido/{id}', [CensoEquidoController::class, 'show'])->name('censo_equidos');
    Route::put('CensoEquido/{id}', [CensoEquidoController::class, 'update'])->name('censo_equidos.update');


    Route::post('CensoOvinoCaprino', [CensoOvinoCaprinoController::class, 'store'])->name('censo_ovino_caprino.store');
    Route::get('CensoOvinoCaprino/{id}', [CensoOvinoCaprinoController::class, 'show'])->name('censo_ovino_caprino');
    Route::put('CensoOvinoCaprino/{id}', [CensoOvinoCaprinoController::class, 'update'])->name('censo_ovino_caprino.update');

    Route::post('CensoOtrasEspec', [CensoOtrasEspecController::class, 'store'])->name('censo_otras_espec.store');
    Route::get('CensoOtrasEspec/{id}', [CensoOtrasEspecController::class, 'show'])->name('censo_otras_espec');
    Route::put('CensoOtrasEspec/{id}', [CensoOtrasEspecController::class, 'update'])->name('censo_otras_espec.update');

    Route::post('CensoPez', [CensoPezController::class, 'store'])->name('censo_peces.store');
    Route::get('CensoPez/{id}', [CensoPezController::class, 'show'])->name('censo_peces');
    Route::put('CensoPez/{id}', [CensoPezController::class, 'update'])->name('censo_peces.update');

    Route::post('CensoCructaceo', [CensoCructaceoController::class, 'store'])->name('censo_cructaceos.store');
    Route::get('CensoCructaceo/{id}', [CensoCructaceoController::class, 'show'])->name('censo_cructaceos');
    Route::put('/censo-cructaceos/{id}', [CensoCructaceoController::class, 'update'])->name('censo_cructaceos.update');


    Route::post('censo_aves_comerciales', [CensoAvesComercialesController::class, 'store'])->name('censo_aves_comerciales.store');
    Route::get('censo_aves_comerciales/{id}', [CensoAvesComercialesController::class, 'show'])->name('censo_aves_comerciales');
    Route::put('censo_aves_comerciales/{id}', [CensoAvesComercialesController::class, 'update'])->name('censo_aves_comerciales.update');


    Route::post('censo_aves_traspatio', [CensoAvesTraspatioController::class, 'store'])->name('censo_aves_traspatio.store');
    Route::get('censo_aves_traspatio/{id}', [CensoAvesTraspatioController::class, 'show'])->name('censo_aves_traspatio');
    Route::put('censo_aves_traspatio/{id}', [CensoAvesTraspatioController::class, 'update'])->name('censo_aves_traspatio.update');


    Route::post('censo_abejas', [CensoAbejasController::class, 'store'])->name('censo_abejas.store');
    Route::get('censo_abejas/{id}', [CensoAbejasController::class, 'show'])->name('censo_abejas');
    Route::put('censo_abejas/{id}', [CensoAbejasController::class, 'update'])->name('censo_abejas.update');

    Route::post('/identificacion-animal', [IdentificacionAnimalController::class, 'store'])->name('identificacionAnimal.store');
    Route::get('/identificacion-animal/{id}', [IdentificacionAnimalController::class, 'show'])->name('identificacionAnimal');
    Route::put('/identificacion-animal/{id}', [IdentificacionAnimalController::class, 'update'])->name('identificacionAnimal.update');


    Route::get('/get-municipios/{departamentoId}', [CensoAbejasController::class, 'getMunicipios']);

    // finanzas
    Route::post('/gastos/store', [GastoController::class, 'store'])->name('gastos.store');
    Route::get('/movimiento/show', [GastoController::class, 'show'])->name('gastos.show');
    Route::put('/gastos/update/{id}', [GastoController::class, 'update'])->name('gastos.update');
    Route::get('/movimientoPredio/{id}', [GastoController::class, 'showPredio'])->name('movimientoPredio');
    Route::get('/gasto/{id}', [GastoController::class, 'showGasto'])->name('GastosPredio');
    Route::get('/Ingresos/{id}', [GastoController::class, 'showIngreso'])->name('IngresosPredio');
    Route::get('/Costos/{id}', [GastoController::class, 'showCostos'])->name('CostosPredio');
    Route::get('/movimientos/export/{id}', [GastoController::class, 'export'])->name('movimientos.export');
    Route::get('/movimientos/exporte/{id}', [GastoController::class, 'showExporte'])->name('movimientos.showExporte');
    Route::get('/movimientos/data/{id}', [GastoController::class, 'getMovimientosData'])->name('movimientos.data');
    Route::delete('/movimiento/{id}', [GastoController::class, 'destroy'])->name('gastos.destroy');
    // rutas para dashboard
    Route::get('/TipoExplotacion/Datos', [TipoExplotacionController::class, 'Datos'])->name('tipoExplotacion.datos');
    Route::get('/Inform-Epidemiologica/Datos', [InforEpidemiologicaController::class, 'Datos'])->name('info-epidemiologica.datos');
    Route::get('/Caracterizacion-Riesgo/Datos', [CarazterizacionRiesgoController::class, 'Datos'])->name('caracterizacion-riesgo.datos');

    //rutas para inventario de animales
    Route::get('/fichaAnimal', [InventarioController::class, 'Index'])->name('inventario.index');
    Route::get('/getAnimals', [InventarioController::class, 'getAnimals'])->name('getAnimals');

    Route::get('/fichaAnimal/{id}', [InventarioController::class, 'update'])->name('inventario.edit');
    //rutas para  animales
    Route::post('/animal/store', [AnimalesController::class, 'store'])->name('storeAnimal.store');
    Route::post('/animal/store', [AnimalesController::class, 'store'])->name('animal.store');
    //rutas para  ubicacion
    Route::get('/ubicacion/index', [UbicacionController::class, 'index'])->name('ubicacion.index');
    Route::post('/ubicacion/store', [UbicacionController::class, 'store'])->name('storeUbicacion.store'); /* aqui guardo los movimientos */
    Route::post('/potreros', [UbicacionController::class, 'storePotrero'])->name('potreros.store');
    Route::post('/lotes', [UbicacionController::class, 'storeLote'])->name('lotes.store');
    Route::get('/animales/potrero/{potreroId}', [UbicacionController::class, 'getAnimalesByPotrero'])->name('animales.byPotrero');
    Route::get('/animales/predio/{predioId}', [UbicacionController::class, 'getAnimalesByPredio'])->name('animales.byPredio');
    Route::get('/animales/lote/{loteId}', [UbicacionController::class, 'getAnimalesByLote'])->name('animales.byLote');

    //rutas para el pesaje
    Route::post('/pesaje_animal/store', [PesajeAnimalController::class, 'store'])->name('storePesaje.store');
    //rutas para el estado
    Route::post('animal/estado', [AnimalEstadoController::class, 'store'])->name('animal.estado.store');

    //rutas para partos
    Route::post('/animales/calcular-dias', [PartosController::class, 'calcularDias'])->name('animales.calcularDias');
    Route::post('/partos/store', [PartosController::class, 'store'])->name('partos.store');
    Route::get('/partos/index', [PartosController::class, 'create'])->name('partos.index');
    Route::get('/partos/historial', [PartosController::class, 'History'])->name('partos.historial');
    Route::get('/partos/template', [PartosController::class, 'downloadTemplate'])->name('partos.template');
    Route::post('/partos/import', [PartosController::class, 'import'])->name('partos.import');

    //rutas para el pesaje de leche
    Route::post('/pesaje_leche/store', [PesajeLecheController::class, 'store'])->name('pesaje_leche.store');
    Route::get('/pesaje_leche/index', [PesajeLecheController::class, 'index'])->name('pesajeLeche.index');
    Route::get('/pesaje_leche/historial', [PesajeLecheController::class, 'historial'])->name('pesajeLeche.historial');
    //rutas para el secado y destete
    Route::get('/secado_destetes', [SecadoDesteteController::class, 'index'])->name('secado_destetes.index');
    Route::get('/secado_destetes/getVacaDetails', [SecadoDesteteController::class, 'getVacaDetails'])->name('secado_destetes.getVacaDetails');
    Route::get('/secado_destetes/getCriaDetails', [SecadoDesteteController::class, 'getCriaDetails'])->name('secado_destetes.getCriaDetails');
    Route::post('/secado_destetes', [SecadoDesteteController::class, 'store'])->name('secado_destetes.store');

    // routes/web.php
    Route::get('/partos/get-vacas', [PartosController::class, 'getVacasByPredios'])->name('partos.getVacasByPredios');
    Route::get('/partos/get-partos-vaca', [PartosController::class, 'getPartosByVaca'])->name('partos.getPartosByVaca');
    Route::get('/partos/get-partos-fecha', [PartosController::class, 'getPartosByFecha'])->name('partos.getPartosByFecha');
    Route::get('/partos/buscar', [PartosController::class, 'buscarPartos'])->name('partos.buscar');
    
    //Monta natural
    Route::get('/MontaNatural', [MontaNaturalController::class, 'index'])->name('MontaNatural.index');
    Route::resource('monta_natural', MontaNaturalController::class)->only(['index', 'store']);
    Route::get('/monta-natural/template', [MontaNaturalController::class, 'downloadTemplate'])->name('monta_natural.template');
    Route::post('/monta-natural/import', [MontaNaturalController::class, 'import'])->name('monta_natural.import');

    //Palpaciones
    Route::get('/Palpaciones', [PalpacionesController::class, 'index'])->name('palpaciones.index');
    Route::post('/Palpaciones/store', [PalpacionesController::class, 'store'])->name('palpaciones.store');
    Route::get('/palpaciones/template', [PalpacionesController::class, 'downloadTemplate'])->name('palpaciones.template');
    Route::post('/palpaciones/import', [PalpacionesController::class, 'import'])->name('palpaciones.import');
   
    //Muerte Animal
    Route::get('/Muerte/index', [MuerteAnimalController::class, 'index'])->name('MuerteAnimal.index');
    Route::post('/Muerte/store', [MuerteAnimalController::class, 'store'])->name('muerte.store');
    
    // Venta Animal
    Route::get('/Venta/index', [VentaAnimalController::class, 'index'])->name('VentaAnimal.index');
    Route::post('/Venta/store', [VentaAnimalController::class, 'store'])->name('VentaAnimal.store');
    
    //veterinario
    Route::get('/Veterinarios/index', [VeterinarioController::class, 'index'])->name('Veterinarios.index');
    
    //reportes
    Route::get('/Reportes/index', [ReportesController::class, 'index'])->name('Reportes.index');

    //proyeccion de partos
    Route::get('/ProyeccionPartos/index', [PartosController::class, 'proyeccionParto'])->name('proyeccionParto.index');
    Route::get('/ProximasPalpaciones/index', [PalpacionesController::class, 'PorPalpar'])->name('HembrasPorPalpar.index');
    Route::get('/DiasAbiertos/index', [PartosController::class, 'diasAbiertos'])->name('diasAbiertos.index');
    Route::get('/inventarioFisico/index', [AnimalesController::class, 'inventarioFisico'])->name('inventarioFisico.index');
    Route::post('/inventarioFisico/store', [AnimalesController::class, 'storeInventario'])->name('inventarioFisico.store');

    // Listados Productivos
Route::prefix('productivos')
    ->middleware('auth')
    ->group(function () {

        // Dashboard Productivos (selector de predio + contadores)
        Route::get('/', [ProductivosController::class, 'index'])
            ->name('productivos.index');

        // CRÍAS
        Route::get('/crias', [ProductivosController::class, 'crias'])
            ->name('productivos.crias');

        // LEVANTES
        Route::get('/levantes/machos', [ProductivosController::class, 'levantesMachos'])
            ->name('productivos.levantes.machos');

        Route::get('/levantes/hembras', [ProductivosController::class, 'levantesHembras'])
            ->name('productivos.levantes.hembras');

        // NOVILLAS DE VIENTRE
        Route::get('/novillas-vientre', [ProductivosController::class, 'novillasVientre'])
            ->name('productivos.novillas.vientre');

        // TOROS
        Route::get('/toros', [ProductivosController::class, 'toros'])
            ->name('productivos.toros');

        // TORETES DE CEBA
        Route::get('/toretes-ceba', [ProductivosController::class, 'toretesCeba'])
            ->name('productivos.toretes.ceba');

        // VACAS PARIDAS
        Route::get('/vacas-paridas', [ProductivosController::class, 'vacasParidas'])
            ->name('productivos.vacas.paridas');

        // VACAS SECAS
        Route::get('/vacas-secas', [ProductivosController::class, 'vacasSecas'])
            ->name('productivos.vacas.secas');

        Route::get('/animal/{id}', [ProductivosController::class, 'detalleAnimal'])
            ->name('productivos.detalle');
    });

    // Listados Reproductivos
    Route::prefix('reproductivos')
    ->middleware('auth')
    ->group(function () {

        Route::get('/', [ReproductivosController::class, 'index'])
            ->name('reproductivos.index');

        Route::get('/vacias', [ReproductivosController::class, 'vacias'])
            ->name('reproductivos.vacias');

        Route::get('/prenadas', [ReproductivosController::class, 'prenadas'])
            ->name('reproductivos.prenadas');

        Route::get('/animal/{id}', [ReproductivosController::class, 'detalleAnimal'])
            ->name('reproductivos.detalle');
    });




    // Ruta para obtener los datos de un inventario específico para edición
    Route::get('/inventarios/{id}/edit', [AnimalesController::class, 'editInventario'])->name('inventarios.edit');

    // Ruta para actualizar un inventario específico
    Route::post('/inventarios/{id}/update', [AnimalesController::class, 'updateInventario'])->name('inventarios.update');
    Route::post('/verificar-codigo-predio', [PrediosController::class, 'verificarCodigoPredio'])->name('predios.verificarCodigo');
    Route::post('/animales/verificar-codigo', [AnimalesController::class, 'verificarCodigo'])->name('animales.verificarCodigo');
    Route::post('/parametro/guardar', [PrediosController::class, 'storeParametros'])->name('parametros.guardar');

    //traslado

    Route::get('/reportes/traslados', [UbicacionController::class, 'reporteTrasladosListado'])
        ->name('reportes.traslados.listado');

    // Ruta para obtener los movimientos de un animal vía AJAX
    Route::get('/reportes/traslados/movimientos-por-animal', [UbicacionController::class, 'movimientosPorAnimal'])
        ->name('reportes.traslados.movimientosPorAnimal');

    //inventario

    Route::get('/inventario/general', [AnimalesController::class, 'inventarioGeneral'])->name('inventario.general');
    Route::post('/inventario/general/filtrar', [AnimalesController::class, 'filtrarInventario'])->name('inventario.filtrar');
    //inseminacion y pajilla
    Route::get('/inseminacion/artificial', [AnimalesController::class, 'inseminacion'])->name('inseminar.index');
    Route::get('/pajillas/index', [AnimalesController::class, 'pajillas'])->name('pajillas.index');
    Route::get('/transferencia/TransferenciaEmbriones', [AnimalesController::class, 'transferenciaHembriones'])->name('transferencia.hembriones');
    Route::post('/transferencias/store', [AnimalesController::class, 'transferenciaHembrionesStore'])->name('transferencias.store');

    Route::get('/transferencias/store/buscarAnimal', [AnimalesController::class, 'buscarAnimalesPorPredio'])->name('buscar.animales.por.predio');


    Route::post('/pajillas/store', [AnimalesController::class, 'pajillasStore'])->name('pajillas.store');
    Route::post('/pajillas/ajuste', [AnimalesController::class, 'pajillasAjuste'])->name('pajillas.ajuste');
    //inseminacion
    Route::post('/inseminacion/registrar', [AnimalesController::class, 'registrarInseminacion'])->name('inseminacion.registrar');
    Route::get('/proyeccion/destetes', [AnimalesController::class, 'proyeccionDestete'])->name('ProyeccionDestetes.index');

    //embriones
    Route::get('/embriones/index', [AnimalesController::class, 'embriones'])->name('embriones.index');
    Route::post('/embriones/index', [AnimalesController::class, 'embrionesPost'])->name('embriones.store');
    Route::get('/embriones/buscar', [AnimalesController::class, 'buscarEmbrionesPorPredio'])
        ->name('buscar.embriones.por.predio');

    //medicacion
    Route::get('/medicacion/index', [AnimalesController::class, 'medicacion'])->name('medicacion.index');
    Route::post('/medicacion/index', [AnimalesController::class, 'medicacionPost'])->name('medicacion.store');

    // Inicio
    Route::get('/moollish/dashboardUser', [InicioController::class, 'dashboardUser'])->name('dashboardUser');
    Route::get('/dashboard', [InicioController::class, 'dashboardAdmin'])->name('dashboardAdmin');
    Route::get('/moollish/caracterizacion', [InicioController::class, 'caracterizacion'])->name('caracterizacion');
    Route::get('/moollish/economia', [InicioController::class, 'economia'])->name('economia');
    Route::get('/moollish/listados', [InicioController::class, 'listados'])->name('listados');
    Route::get('/moollish/registros', [InicioController::class, 'registros'])->name('registros');
    Route::get('/moollish/animales', [InicioController::class, 'animales'])->name('animales');
    Route::get('/moollish/reproduccionAnimal', [InicioController::class, 'reproduccionAnimal'])->name('reproduccionAnimal');
    Route::get('/moollish/produccionAnimal', [InicioController::class, 'produccionAnimal'])->name('produccionAnimal');
    //membresias
    Route::get('/moollish/ajustes', [UserController::class, 'ajustes'])->name('ajustes');

    // Rutas para Insumos
    Route::get('/insumos', [InsumosController::class, 'index'])->name('insumos.index');
    Route::get('/insumos/consulta', [InsumosController::class, 'consultaView'])->name('insumos.consulta');
    Route::get('/insumos/registrar', [InsumosController::class, 'registroForm'])->name('insumos.registroForm');
    Route::get('/insumos/entrada', [InsumosController::class, 'entradaForm'])->name('insumos.entrada');
    Route::post('/insumos', [InsumosController::class, 'store'])->name('insumos.store');
    Route::post('/insumos/entrada', [InsumosController::class, 'entradaStore'])->name('insumos.entrada.store');
    Route::get('/insumos/{id}', [InsumosController::class, 'show'])->name('insumos.show');
    Route::get('/insumos/{id}/edit', [InsumosController::class, 'edit'])->name('insumos.edit');
    Route::put('/insumos/{id}', [InsumosController::class, 'update'])->name('insumos.update');
    Route::delete('/insumos/{id}', [InsumosController::class, 'destroy'])->name('insumos.destroy');
    Route::get('/insumos/tipos-uso/{categoriaId}', [InsumosController::class, 'getTiposUso'])->name('insumos.getTiposUso');
    Route::post('/insumos/registrar-movimiento', [InsumosController::class, 'registrarMovimiento'])->name('insumos.registrarMovimiento');
    Route::get('/insumos/{id}/historial', [InsumosController::class, 'historial'])->name('insumos.historial');
    Route::put('/insumos/{insumo}/movimientos/eliminar-movimiento/{movimientoId}', [InsumosController::class, 'destroyMovimiento'])->name('insumos.destroyMovimiento');

    // Insumos - Salida
    Route::get('/insumosSalida', [AplicacionInsumosController::class, 'salidaForm'])->name('insumos.salida');
    Route::post('/insumos/salida', [AplicacionInsumosController::class, 'salidaStore'])->name('insumos.salida.store');

    // Aplicación de Insumos (Salida) routes
    Route::get('/insumos/por-predio/{predio}', [InsumoController::class, 'getInsumosPorPredio'])->name('insumos.porPredio');
    Route::get('/insumos/tipos-uso-insumo/{insumoId}', [AplicacionInsumosController::class, 'getTiposUsoInsumo'])->name('insumos.tiposUsoInsumo');
    Route::get('/potreros/por-predio/{predioId}', [AplicacionInsumosController::class, 'getPotrerosPorPredio'])->name('potreros.porPredio');
    Route::get('/lotes/por-predio/{predioId}', [AplicacionInsumosController::class, 'getLotesPorPredio'])->name('lotes.porPredio');

    // Rutas para Usuarios (gestión)
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::post('users', [UserController::class, 'store'])->name('users.store'); // Mantenemos la de store AJAX
    // Rutas para edición AJAX
    Route::get('users/get-data/{id}', [UserController::class, 'getData'])->name('users.getData');
    Route::put('users/update-ajax/{id}', [UserController::class, 'updateAjax'])->name('users.updateAjax');

    Route::get('users/{id}', [UserController::class, 'show'])->name('users.show');
    Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('users.edit'); // Puede que ya no se use si todo es modal
    // Route::put('users/{user}', [UserController::class, 'update'])->name('users.update'); // Comentar o eliminar si se reemplaza por updateAjax
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // Ruta para mostrar la política de uso
    Route::get('/politica-uso', function () {
        return view('documentos.politicaUso');
    })->name('politica.uso');

    // Ruta para mostrar la información legal
    Route::get('/informacion-legal', function () {
        return view('documentos.informacionLegal');
    })->name('informacion.legal');

});
Route::post('/animales/import', [AnimalesController::class, 'import'])->name('animales.import');
Route::get('/animales/template', [AnimalesController::class, 'downloadTemplate'])->name('animales.template');

// Rutas para membresias
Route::middleware(['auth'])->group(function () {
    Route::post('/membresias/asignar', [UserController::class, 'asignarMembresia'])->name('membresias.asignar');
    Route::get('/moollish/inicio', [InicioController::class, 'inicio'])->name('inicio');
    Route::get('/membresias', [UserController::class, 'mostrarMembresias'])->name('membresias');
    Route::post('/membresias/solicitar', [UserController::class, 'solicitarMembresia'])->name('solicitar.membresia');
    Route::post('/membresias/actualizar/{membership}', [UserController::class, 'actualizarMembresia'])->name('membresias.update');
    Route::get('/membresias/free-trial', [UserController::class, 'activarFreeTrial'])->name('activar.freeTrial');
    Route::post('/membresias/free-trial', [UserController::class, 'solicitarFreeTrial'])->name('solicitar.freeTrial');
});

Route::get('/offline-content', function () {
    return view('offline-content');
})->name('offline.content');


// Ruta para obtener los predios del usuario autenticado (usando autenticación de sesión web)
Route::middleware('auth')->get('/web/predios', function (Request $request) {
    // Obtener el usuario autenticado
    $user = $request->user();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'No autenticado'
        ], 401);
    }

    try {
        // Aquí debes adaptar esto según tu modelo de datos - esta es una implementación básica
        $predios = null;

        // Buscar los predios del usuario según la relación en tu modelo
        if (method_exists($user, 'predios')) {
            $predios = $user->predios()->get();
        } else {
            // Si no hay relación directa, buscar por user_id
            $predios = Predios::where('user_id', $user->id)
                        ->orWhere('creado_por', $user->id)
                        ->get();
        }

        return response()->json([
            'success' => true,
            'predios' => $predios
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al cargar predios: ' . $e->getMessage()
        ], 500);
    }
});

// Ruta para obtener detalles de un predio específico (autenticación de sesión web)
Route::middleware('auth')->get('/web/predio/{id}/details', function (Request $request, $id) {
    // Obtener el usuario autenticado
    $user = $request->user();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'No autenticado'
        ], 401);
    }

    try {
        // Buscar el predio
        $predio = null;

        try {
            $predio = Predios::findOrFail($id);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Predio no encontrado'
            ], 404);
        }

        // Verificar que el usuario tenga acceso a este predio
        $hasAccess = false;

        // Si existe una relación directa con el usuario
        if (method_exists($user, 'predios')) {
            $hasAccess = $user->predios()->where('id', $id)->exists();
        } else {
            // Por user_id o creado_por
            $hasAccess = ($predio->user_id == $user->id || $predio->creado_por == $user->id);
        }

        if (!$hasAccess) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes acceso a este predio'
            ], 403);
        }

        // Obtener datos adicionales necesarios para la edición
        $data = [
            'predio' => $predio,
            // Agregar otros datos necesarios según sea necesario
        ];

        return response()->json([
            'success' => true,
            'data' => $data
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al cargar detalles del predio: ' . $e->getMessage()
        ], 500);
    }
});

// Ruta para el registro de pesos
Route::get('/animales/registro-peso', [App\Http\Controllers\PesajeAnimalController::class, 'index'])->name('registro_peso');

Route::post('/pesaje', [App\Http\Controllers\PesajeAnimalController::class, 'store'])->name('pesaje.store');

// Ruta para recibir animales creados offline y sincronizarlos
Route::post('/animal/store/offline', [AnimalesController::class, 'storeOffline'])->name('animal.store.offline');


