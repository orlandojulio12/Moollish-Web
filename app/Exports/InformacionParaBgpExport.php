<?php

namespace App\Exports;

use App\Models\InformacionBgp;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;

class InformacionParaBgpExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    /**
     * Retornar los datos de la tabla 'informacion_bgp' con relaciones
     */
    public function collection()
    {
        return InformacionBgp::with(['predio', 'tiposInformacionBgp'])->get();
    }

    /**
     * Mapeo de las columnas para que muestre los nombres en lugar de los IDs
     */
    public function map($informacionBgp): array
    {
        return [
            $informacionBgp->predio ? $informacionBgp->predio->nombre_predio : 'Sin predio',
            $informacionBgp->tiposInformacionBgp ? $informacionBgp->tiposInformacionBgp->nombre : 'Sin tipo',
            $informacionBgp->estado,
        ];
    }

    /**
     * Definir las cabeceras del archivo Excel
     */
    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Tipo de Información BGP',
            'Estado',
        ];
    }

    /**
     * Aplicar estilos y espaciar las celdas
     */
    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:C1')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        foreach (range('A', 'C') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    /**
     * Definir el nombre de la hoja de Excel
     */
    public function title(): string
    {
        return 'INFORMACION PARA BPG';
    }
}
