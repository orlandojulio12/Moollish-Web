<?php

namespace App\Exports;

use App\Models\Animal;
use App\Models\EstadoProductivo;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class MontaTemplateExport implements FromArray, WithStyles, WithTitle, WithDrawings
{
    protected $predio_id;

    public function __construct($predio_id)
    {
        $this->predio_id = $predio_id;
    }

    public function array(): array
    {
        // Obtener vacas del predio en estados válidos
        $vacas = Animal::where('id_predio', $this->predio_id)
            ->where('sexo', 'hembra')
            ->whereIn('estado_productivo_id', [
                EstadoProductivo::HEMBRA_LEVANTE,
                EstadoProductivo::NOVILLA_VIENTRE,
                EstadoProductivo::VACA_SECA,
                EstadoProductivo::VACA_PARIDA,
            ])
            ->get()
            ->map(function($vaca) {
                return $vaca->codigo . ($vaca->nombre ? ' - ' . $vaca->nombre : '');
            })
            ->toArray();

        // Obtener toros del predio en estados válidos
        $toros = Animal::where('id_predio', $this->predio_id)
            ->where('sexo', 'macho')
            ->whereIn('estado_productivo_id', [
                EstadoProductivo::MACHO_LEVANTE,
                EstadoProductivo::MACHO_CEBA,
                EstadoProductivo::REPRODUCTOR_TORO,
            ])
            ->get()
            ->map(function($toro) {
                return $toro->codigo . ($toro->nombre ? ' - ' . $toro->nombre : '');
            })
            ->toArray();

        return [
            ['PLANTILLA DE IMPORTACIÓN DE MONTA NATURAL'],
            ['📋 INSTRUCCIONES DE USO'],
            ['1. Complete los datos a partir de la fila 11'],
            ['2. Campos obligatorios: Código Vaca, Código Toro, Fecha Monta'],
            ['3. Formato de fecha: dd/mm/aaaa (Ejemplo: 15/01/2025)'],
            ['4. La fecha de monta no puede ser futura (debe ser hoy o anterior)'],
            ['5. Solo puede seleccionar vacas y toros del desplegable (estados válidos)'],
            ['6. ⚠️ IMPORTANTE: Los códigos deben coincidir exactamente con los del sistema'],
            [''],
            ['Código Vaca', 'Código Toro', 'Fecha Monta'],
            [$vacas[0] ?? 'Ejemplo-001', $toros[0] ?? 'Toro-001', '15/01/2025'],
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Moollish Logo');
        $drawing->setPath(public_path('img/logo-letra1.png'));
        $drawing->setHeight(100);
        $drawing->setCoordinates('D2');
        return file_exists(public_path('img/logo-letra1.png')) ? $drawing : [];
    }

    public function styles(Worksheet $sheet)
    {
        // Título
        $sheet->mergeCells('A1:C1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'e49b39']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(35);

        // Instrucciones
        $sheet->mergeCells('A2:C2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFF4E6']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
        ]);

        for ($i = 3; $i <= 8; $i++) {
            $sheet->mergeCells("A{$i}:C{$i}");
            $sheet->getStyle("A{$i}")->applyFromArray([
                'font' => ['size' => 10, 'color' => ['rgb' => '333333']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFBF5']],
            ]);
        }

        $sheet->mergeCells('A9:C9');
        $sheet->getStyle('A9')->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFBF5']],
        ]);

        // Encabezados
        $sheet->getStyle('A10:C10')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'e49b39']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
        $sheet->getRowDimension(10)->setRowHeight(25);

        // Datos
        $sheet->getStyle('A11:C100')->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Obtener vacas y toros para dropdowns
        $vacas = Animal::where('id_predio', $this->predio_id)
            ->where('sexo', 'hembra')
            ->whereIn('estado_productivo_id', [
                EstadoProductivo::HEMBRA_LEVANTE,
                EstadoProductivo::NOVILLA_VIENTRE,
                EstadoProductivo::VACA_SECA,
                EstadoProductivo::VACA_PARIDA,
            ])
            ->get()
            ->map(function($vaca) {
                return $vaca->codigo . ($vaca->nombre ? ' - ' . $vaca->nombre : '');
            })
            ->toArray();

        $toros = Animal::where('id_predio', $this->predio_id)
            ->where('sexo', 'macho')
            ->whereIn('estado_productivo_id', [
                EstadoProductivo::MACHO_LEVANTE,
                EstadoProductivo::MACHO_CEBA,
                EstadoProductivo::REPRODUCTOR_TORO,
            ])
            ->get()
            ->map(function($toro) {
                return $toro->codigo . ($toro->nombre ? ' - ' . $toro->nombre : '');
            })
            ->toArray();

        $vacasList = implode(',', $vacas);
        $torosList = implode(',', $toros);

        // Dropdown: Vacas (columna A)
        if (!empty($vacas)) {
            for ($row = 11; $row <= 100; $row++) {
                $validation = $sheet->getCell("A{$row}")->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_STOP);
                $validation->setAllowBlank(false);
                $validation->setShowDropDown(true);
                $validation->setErrorTitle('Vaca inválida');
                $validation->setError('Seleccione una vaca válida del predio');
                $validation->setFormula1('"'.$vacasList.'"');
            }
        }

        // Dropdown: Toros (columna B)
        if (!empty($toros)) {
            for ($row = 11; $row <= 100; $row++) {
                $validation = $sheet->getCell("B{$row}")->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_STOP);
                $validation->setAllowBlank(false);
                $validation->setShowDropDown(true);
                $validation->setErrorTitle('Toro inválido');
                $validation->setError('Seleccione un toro válido del predio');
                $validation->setFormula1('"'.$torosList.'"');
            }
        }

        // Anchos de columna
        $sheet->getColumnDimension('A')->setWidth(20);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(50);

        // Formato TEXTO para códigos (columnas A y B)
        $sheet->getStyle('A:A')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
        $sheet->getStyle('B:B')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
        // Formato fecha para columna C
        $sheet->getStyle('C:C')->getNumberFormat()->setFormatCode('dd/mm/yyyy');

        return [];
    }

    public function title(): string
    {
        return 'Monta Natural Moollish';
    }
}