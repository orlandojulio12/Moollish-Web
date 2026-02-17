<?php

namespace App\Exports;

use App\Models\ServiciosAmbientales;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class ServiciosAmbientalesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    protected $prediosFiltrados;

    /**
     * Constructor para recibir los predios filtrados
     */
    public function __construct($prediosFiltrados = 'all')
    {
        $this->prediosFiltrados = $prediosFiltrados;
    }

    /**
     * Retornar los datos filtrados de la tabla 'servicios_ambientales' con relaciones
     */
    public function collection()
    {
        $query = ServiciosAmbientales::with(['predio', 'tpserviciosAmbientales']);

        // Aplicar filtro de predios si no es 'all'
        if ($this->prediosFiltrados !== 'all' && !empty($this->prediosFiltrados)) {
            $query->whereIn('id_predio', $this->prediosFiltrados);
        }

        return $query->orderBy('id_predio')->get();
    }

    /**
     * Mapeo de las columnas
     */
    public function map($servicioAmbiental): array
    {
        return [
            $servicioAmbiental->predio ? $servicioAmbiental->predio->nombre_predio : 'Sin predio',
            $servicioAmbiental->tpserviciosAmbientales ? $servicioAmbiental->tpserviciosAmbientales->nombre : 'Sin tipo',
            $servicioAmbiental->hectareas,
            $servicioAmbiental->materiales_establecidos,
            $servicioAmbiental->sum_total,
            $servicioAmbiental->created_at ? $servicioAmbiental->created_at->format('Y-m-d H:i:s') : '',
            $servicioAmbiental->updated_at ? $servicioAmbiental->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }

    /**
     * Definir las cabeceras del archivo Excel
     */
    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Tipo de Servicio Ambiental',
            'Hectáreas',
            'Materiales Establecidos',
            'Total',
            'Fecha de Creación',
            'Fecha de Actualización',
        ];
    }

    /**
     * Aplicar estilos
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo de cabeceras
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '70AD47']
            ]
        ]);

        // Aumentar el ancho de las columnas
        foreach (range('A', 'G') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    /**
     * Definir el título de la hoja de Excel
     */
    public function title(): string
    {
        return 'SERVICIOS AMBIENTALES';
    }
}