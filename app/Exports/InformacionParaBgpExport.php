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
    protected $prediosFiltrados;

    /**
     * Constructor para recibir los predios filtrados
     */
    public function __construct($prediosFiltrados = 'all')
    {
        $this->prediosFiltrados = $prediosFiltrados;
    }

    /**
     * Retornar los datos filtrados de la tabla 'informacion_bgp' con relaciones
     */
    public function collection()
    {
        $query = InformacionBgp::with(['predio', 'tiposInformacionBgp']);

        // Aplicar filtro de predios si no es 'all'
        if ($this->prediosFiltrados !== 'all' && !empty($this->prediosFiltrados)) {
            $query->whereIn('id_predio', $this->prediosFiltrados);
        }

        return $query->orderBy('id_predio')->get();
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
            $informacionBgp->created_at ? $informacionBgp->created_at->format('Y-m-d H:i:s') : '',
            $informacionBgp->updated_at ? $informacionBgp->updated_at->format('Y-m-d H:i:s') : '',
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
            'Fecha de Creación',
            'Fecha de Actualización',
        ];
    }

    /**
     * Aplicar estilos y espaciar las celdas
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo de cabeceras en negrita y con fondo
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF']
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFA500']  // Naranja para BPG
            ]
        ]);

        // Aumentar el ancho de las columnas
        foreach (range('A', 'E') as $columnID) {
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