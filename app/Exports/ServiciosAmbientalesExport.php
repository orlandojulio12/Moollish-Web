<?php

namespace App\Exports;

use App\Models\ServiciosAmbientales;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ServiciosAmbientalesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    /**
     * Retornar los datos de la tabla 'servicios_ambientales' con relaciones
     */
    public function collection()
    {
        return ServiciosAmbientales::with(['predio', 'tpserviciosAmbientales'])->get();
    }

    /**
     * Mapeo de las columnas para que muestre los nombres en lugar de los IDs
     */
    public function map($servicioAmbiental): array
    {
        return [
            $servicioAmbiental->predio ? $servicioAmbiental->predio->nombre_predio : 'Sin predio',  // Nombre del predio
            $servicioAmbiental->tpserviciosAmbientales ? $servicioAmbiental->tpserviciosAmbientales->nombre : 'Sin tipo de servicio',  // Nombre del tipo de servicio ambiental
            $servicioAmbiental->hectareas,
            $servicioAmbiental->materiales_establecidos,
            $servicioAmbiental->sum_total,
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
            'Suma Total',
        ];
    }

    /**
     * Aplicar estilos y espaciar las celdas
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo de cabeceras en negrita
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        // Aumentar el ancho de las columnas
        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    /**
     * Definir el título de la hoja
     */
    public function title(): string
    {
        return 'SERVICIOS AMBIENTALES';
    }
}
