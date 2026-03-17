<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class PrenecesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    protected Collection $data;

    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    public function collection(): Collection
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'Código',
            'Nombre',
            'Raza',
            'Lote',
            'Potrero',
            'Tipo Concepción',
            'Fecha Concepción',
            'Días Transcurridos',
            'Días Esperados',
            '% Gestación',
            'Parto Proyectado',
            'Días para Parto',
            'Fecha Última Palpación',
            'Resultado Palpación',
        ];
    }

    public function map($row): array
    {
        $row = (array) $row;
        return [
            $row['codigo'],
            $row['nombre'],
            $row['raza'],
            $row['lote'] ?? '-',
            $row['potrero'] ?? '-',
            $row['tipo_concepcion'] ?? '-',
            $row['fecha_concepcion'] ?? '-',
            $row['dias_gestacion_transcurridos'] ?? '-',
            $row['dias_gestacion_esperados'],
            $row['porcentaje_gestacion'] !== null ? $row['porcentaje_gestacion'] . '%' : '-',
            $row['parto_proyectado'] ?? '-',
            $row['dias_para_parto'] !== null ? $row['dias_para_parto'] . ' días' : '-',
            isset($row['ultima_palpacion']['fecha']) ? $row['ultima_palpacion']['fecha'] : '-',
            isset($row['ultima_palpacion']['resultado']) ? $row['ultima_palpacion']['resultado'] : '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['argb' => 'FFE49B39']],
            ],
        ];
    }

    public function title(): string
    {
        return 'Preñeces';
    }
}
