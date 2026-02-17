<?php

namespace App\Http\Controllers;

use App\Models\Predios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\InformacionDelPredioExport;
use App\Exports\InformacionParaBgpExport;
use App\Exports\RiesgoEpidemiologicoExport;
use App\Exports\ServiciosAmbientalesExport;
use App\Exports\CensoExport;
use Maatwebsite\Excel\Facades\Excel;

class exportePredioController extends Controller
{
    /**
     * Mostrar la vista de exportación
     */
    public function show()
    {
        // Cargar TODOS los predios sin filtrar por usuario
        $predios = Predios::select('id', 'cod_predio', 'nombre_predio')
            ->orderBy('nombre_predio')
            ->get();
        
        return view('exportePredio.show', compact('predios'));
    }

    /**
     * Buscar predios por nombre o código (para Select2 AJAX)
     */
    public function buscarPredios(Request $request)
    {
        $search = $request->get('q', '');
        $user = Auth::user();
        
        $query = Predios::select('id', 'nombre_predio', 'cod_predio');
        
        // Si NO es admin, solo sus predios
        if ($user->role_id != 1) {
            $query->whereHas('users', function($q) use ($user) {
                $q->where('users.id', $user->id);
            });
        }
        
        // Búsqueda por nombre O código (SIEMPRE retorna predios, incluso sin búsqueda)
        if (!empty($search)) {
            $query->where(function($q) use ($search) {
                $q->where('nombre_predio', 'LIKE', "%{$search}%")
                  ->orWhere('cod_predio', 'LIKE', "%{$search}%");
            });
        }
        
        // SIEMPRE retorna predios (hasta 100 si no hay búsqueda)
        $limit = empty($search) ? 100 : 50;
        $predios = $query->orderBy('nombre_predio')
                         ->limit($limit)
                         ->get();
        
        // Formatear para Select2
        $results = $predios->map(function($predio) {
            return [
                'id' => $predio->id,
                'text' => $predio->cod_predio . ' - ' . $predio->nombre_predio
            ];
        });
        
        return response()->json([
            'results' => $results
        ]);
    }

    /**
     * Obtener los predios filtrados desde el request
     */
    private function getPrediosFromRequest(Request $request)
    {
        $predios = $request->input('predios', 'all');
        
        if ($predios === 'all' || empty($predios)) {
            return 'all';
        }

        // Si viene como string separado por comas, convertir a array
        if (is_string($predios)) {
            $predios = explode(',', $predios);
        }

        // Filtrar valores vacíos y "all"
        $predios = array_filter($predios, function($value) {
            return !empty($value) && $value !== 'all';
        });

        return empty($predios) ? 'all' : array_values($predios);
    }

    /**
     * Exportar Información del Predio
     */
    public function exportarInformacionDelPredio(Request $request)
    {
        try {
            $prediosFiltrados = $this->getPrediosFromRequest($request);
            
            return Excel::download(
                new InformacionDelPredioExport($prediosFiltrados),
                'informacion_del_predio_' . date('Y-m-d_His') . '.xlsx'
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar el archivo: ' . $e->getMessage());
        }
    }

    /**
     * Exportar Información para BGP
     */
    public function exportBgp(Request $request)
    {
        try {
            $prediosFiltrados = $this->getPrediosFromRequest($request);
            
            return Excel::download(
                new InformacionParaBgpExport($prediosFiltrados),
                'informacion_para_bgp_' . date('Y-m-d_His') . '.xlsx'
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar el archivo: ' . $e->getMessage());
        }
    }

    /**
     * Exportar Riesgo Epidemiológico
     */
    public function exportRiesgoEpidemiologico(Request $request)
    {
        try {
            $prediosFiltrados = $this->getPrediosFromRequest($request);
            
            return Excel::download(
                new RiesgoEpidemiologicoExport($prediosFiltrados),
                'riesgo_epidemiologico_' . date('Y-m-d_His') . '.xlsx'
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar el archivo: ' . $e->getMessage());
        }
    }

    /**
     * Exportar Servicios Ambientales
     */
    public function exportServiciosAmbientales(Request $request)
    {
        try {
            $prediosFiltrados = $this->getPrediosFromRequest($request);
            
            return Excel::download(
                new ServiciosAmbientalesExport($prediosFiltrados),
                'servicios_ambientales_' . date('Y-m-d_His') . '.xlsx'
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar el archivo: ' . $e->getMessage());
        }
    }

    /**
     * Exportar Censo
     */
    public function exportCenso(Request $request)
    {
        try {
            $prediosFiltrados = $this->getPrediosFromRequest($request);
            
            return Excel::download(
                new CensoExport($prediosFiltrados),
                'censo_' . date('Y-m-d_His') . '.xlsx'
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al generar el archivo: ' . $e->getMessage());
        }
    }
}