<?php

namespace App\Http\Controllers;
use App\Models\Predios;
use App\Exports\InformacionDelPredioExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Exports\InformacionParaBgpExport;
use App\Exports\RiesgoEpidemiologicoExport;
use App\Exports\ServiciosAmbientalesExport;
use App\Exports\CensoExport;

class exportePredioController extends Controller
{
    public function show()
    {
        return view('exportePredio.show');
    }

    public function exportarInformacionDelPredio()
    {
        return Excel::download(new InformacionDelPredioExport, 'INFORMACION_DEL_PREDIO.xlsx');
    }

    public function exportBgp()
    {
        return Excel::download(new InformacionParaBgpExport, 'informacion_para_bgp.xlsx');
    }
    public function exportRiesgoEpidemiologico()
    {
        // Exportar y descargar el archivo Excel
        return Excel::download(new RiesgoEpidemiologicoExport, 'RIESGO_EPIDEMIOLOGICO.xlsx');
    }
    public function exportServiciosAmbientales()
    {
        // Retorna la descarga del archivo Excel con el nombre 'SERVICIOS_AMBIENTALES.xlsx'
        return Excel::download(new ServiciosAmbientalesExport, 'SERVICIOS_AMBIENTALES.xlsx');
    }
    public function exportCenso()
    {
        // Retorna la descarga del archivo Excel con el nombre 'CENSO.xlsx'
        return Excel::download(new CensoExport, 'CENSO.xlsx');
    }
    
}
