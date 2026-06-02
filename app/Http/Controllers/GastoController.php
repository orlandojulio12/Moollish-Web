<?php

namespace App\Http\Controllers;

use App\Models\Movimientos;
use App\Models\Predios;
use App\Models\User;
use App\Models\PlanCuenta;
use Illuminate\Http\Request;
use App\Exports\MovimientosExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class GastoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {

        try {
            // Validar los datos
            $validated = $request->validate([
                'usuario_id' => 'required|integer',
                'id_predio' => 'required|integer',
                'plan_cuenta' => 'required|integer',
                'cantidad' => 'required|integer',
                'descripcion' => 'required|string',
                'fecha' => 'required|date',
            ]);

            // Crear el nuevo registro
            $gasto = Movimientos::create($validated);

            // Retornar una respuesta exitosa
            return redirect()->back()->with('success', 'Movimiento creado exitosamente');

        } catch (\Exception $e) {
            // Capturar y manejar errores
            return redirect()->back()->with('error', 'Error al crear el gasto: ' . $e->getMessage());
        }
    }

   /* public function show(Request $request)
    {
        $user = Auth::user();

        $predios = collect(); // Vacío por defecto

        if ($user->role->name === 'admin') {
            $predios = Predios::all(); // Obtener todos los predios
        }

        if ($user->role->name === 'propietario') {
            $predios = Predios::whereHas('usuarios', function ($query) use ($user) {
                $query->where('users.id', $user->id); // Especificar explícitamente la tabla 'users'
            })->get(); // Obtener sin paginar
        }

        $clase = PlanCuenta::whereRaw('LENGTH(codcta) = 6')->get();

        $gastos = Movimientos::whereIn('id_predio', $predios->pluck('id'))->get();


        $planCuentas = PlanCuenta::all();

        return view('gastos.show', compact('clase', 'predios', 'gastos', 'planCuentas', 'user'));
    }*/

     public function show(Request $request)
{
    $user = Auth::user();

    // Inicializar variables
    $Predios = collect();
    $saldo = 0;

    if ($user->role->name === 'admin') {
        $Predios = Predios::all();
        
        // CARGAR LA RELACIÓN planCuenta
        $gastos = Movimientos::with('planCuenta')
            ->whereIn('id_predio', $Predios->pluck('id'))
            ->get();
    } else {
        $Predios = Predios::whereHas('usuarios', function ($query) use ($user) {
            $query->where('users.id', $user->id);
        })->get();

        // CARGAR LA RELACIÓN planCuenta
        $gastos = Movimientos::with('planCuenta')
            ->whereIn('id_predio', $Predios->pluck('id'))
            ->get();
    }

    // Calcular saldo CON VERIFICACIÓN
    foreach ($gastos as $gasto) {
        // Verificar que planCuenta existe y tiene naturaleza
        if ($gasto->planCuenta && $gasto->planCuenta->naturaleza) {
            if ($gasto->planCuenta->naturaleza === 'Ingresos' || 
                $gasto->planCuenta->naturaleza === 'Activos') {
                $saldo += abs($gasto->cantidad);
            } else {
                $saldo -= abs($gasto->cantidad);
            }
        }
    }

    $clase = PlanCuenta::whereRaw('LENGTH(codcta) = 6')->get();
    $planCuentas = PlanCuenta::all();
    $categoriasPrincipales = PlanCuenta::whereRaw('LENGTH(codcta) = 6')->get();
    $subcuentasDetalles = PlanCuenta::whereRaw('LENGTH(codcta) = 8')->get();

    return view('gastos.show', compact('clase', 'categoriasPrincipales',
        'subcuentasDetalles', 'Predios', 'gastos', 'planCuentas', 'user', 'saldo'));
}



    public function showPredio($user_id)
{
    $user = User::findOrFail($user_id);

    // Obtener los predios relacionados
    $Predios = $user->predios; 

    // Si quieres solo el primero:
    // $predio = $user->predios()->first();

    $categoriasPrincipales = PlanCuenta::whereRaw('LENGTH(codcta) = 6')->get();
    $subcuentasDetalles = PlanCuenta::whereRaw('LENGTH(codcta) = 8')->get();
    $planCuentas = PlanCuenta::all();

    // Si quieres traer movimientos de todos los predios de ese usuario:
    $gastos = Movimientos::whereIn('id_predio', $Predios->pluck('id'))->get();

    // Calcular saldo
    $saldo = 0;
    foreach ($gastos as $gasto) {
        if ($gasto->planCuenta->naturaleza === 'Ingresos' || $gasto->planCuenta->naturaleza === 'Activos') {
            $saldo += abs($gasto->cantidad);
        } else {
            $saldo -= abs($gasto->cantidad);
        }
    }

    return view('gastos.showPredio', compact(
        'categoriasPrincipales',
        'subcuentasDetalles',
        'gastos',
        'planCuentas',
        'Predios',
        'saldo'
    ));
}


    public function showGasto($id)
    {
        $Predios = Predios::findOrFail($id)->id;

        // Obtener todas las categorías principales (6 dígitos) y subcuentas (8 dígitos)
        $categoriasPrincipales = PlanCuenta::whereRaw('LENGTH(codcta) = 6')->get();
        $subcuentasDetalles = PlanCuenta::whereRaw('LENGTH(codcta) = 8')->get();

        $gastos = Movimientos::where('id_predio', $Predios)
        ->whereHas('planCuenta', function ($query) {
            $query->where('naturaleza', 'Gastos');
        })
        ->get();
        $planCuentas = PlanCuenta::all();

        // Pasar los datos a la vista
        return view('gastos.gastos', compact('categoriasPrincipales', 'subcuentasDetalles', 'gastos', 'planCuentas', 'Predios'));
    }

    public function showIngreso($id)
    {
        $Predios = Predios::findOrFail($id)->id;

        // Obtener todas las categorías principales (6 dígitos) y subcuentas (8 dígitos)
        $categoriasPrincipales = PlanCuenta::whereRaw('LENGTH(codcta) = 6')->get();
        $subcuentasDetalles = PlanCuenta::whereRaw('LENGTH(codcta) = 8')->get();

        $gastos = Movimientos::where('id_predio', $Predios)
        ->whereHas('planCuenta', function ($query) {
            $query->where('naturaleza', 'Ingresos');
        })
        ->get();
        $planCuentas = PlanCuenta::all();

        // Pasar los datos a la vista
        return view('gastos.ingresos', compact('categoriasPrincipales', 'subcuentasDetalles', 'gastos', 'planCuentas', 'Predios'));
    }

    public function showCostos($id)
    {
        $Predios = Predios::findOrFail($id)->id;

        // Obtener todas las categorías principales (6 dígitos) y subcuentas (8 dígitos)
        $categoriasPrincipales = PlanCuenta::whereRaw('LENGTH(codcta) = 6')->get();
        $subcuentasDetalles = PlanCuenta::whereRaw('LENGTH(codcta) = 8')->get();

        $gastos = Movimientos::where('id_predio', $Predios)
        ->whereHas('planCuenta', function ($query) {
            $query->where('naturaleza', 'Costos de ventas');
        })
        ->get();
        $planCuentas = PlanCuenta::all();

        // Pasar los datos a la vista
        return view('gastos.ingresos', compact('categoriasPrincipales', 'subcuentasDetalles', 'gastos', 'planCuentas', 'Predios'));
    }

    public function showExporte($id)
    {
        $predioId = Predios::findOrFail($id)->id;

        // Pasar los datos a la vista con el id del predio
        return view('gastos.exporte', compact('predioId'));
    }

    public function getMovimientosData(Request $request, $id)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Obtener los movimientos filtrados por predio y fecha
        $movimientos = Movimientos::with('planCuenta')
            ->where('id_predio', $id)
            ->whereBetween('fecha', [$startDate, $endDate])
            ->get();

        // Agrupar los movimientos por naturaleza y calcular las cantidades
        $data = [
            'Costos de ventas' => $movimientos->where('planCuenta.naturaleza', 'Costos de ventas')->sum('cantidad'),
            'Gastos' => $movimientos->where('planCuenta.naturaleza', 'Gastos')->sum('cantidad'),
            'Ingresos' => $movimientos->where('planCuenta.naturaleza', 'Ingresos')->sum('cantidad'),
        ];

        return response()->json($data);
    }

    public function export(Request $request, $id)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        return Excel::download(new MovimientosExport($id, $startDate, $endDate), 'movimientos.xlsx');
    }


    public function update(Request $request, $id)
    {

        try {
            // Encontrar el registro existente
            $gasto = Movimientos::findOrFail($id);

            // Validar los datos
            $validated = $request->validate([
                'usuario_id' => 'required|integer',
                'id_predio' => 'required|integer',
                'plan_cuenta' => 'required|integer',
                'cantidad' => 'required|integer',
                'descripcion' => 'required|string',
                'fecha' => 'required|date',
            ]);


            // Actualizar el registro
            $gasto->update($validated);

            // Retornar una respuesta exitosa
            return redirect()->back()->with('success', 'Movimiento actualizado exitosamente');

        } catch (\Exception $e) {
            // Capturar y manejar errores
            return redirect()->back()->with('error', 'Error al actualizar el gasto: ' . $e->getMessage());
        }
    }

    public function destroy($id)
{
    try {
        // Encontrar el registro existente
        $gasto = Movimientos::findOrFail($id);

        // Eliminar el registro
        $gasto->delete();

        // Retornar una respuesta exitosa
        return redirect()->back()->with('success', 'Movimineto eliminado exitosamente');

    } catch (\Exception $e) {
        // Capturar y manejar errores
        return redirect()->back()->with('error', 'Error al eliminar el gasto: ' . $e->getMessage());
    }
}



}
