<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class AnimalesTemplateExport implements FromArray, WithStyles, WithTitle, WithDrawings
{
    public function array(): array
{
    return [
        ['PLANTILLA DE IMPORTACIÓN DE ANIMALES'], // Fila 1
        ['📋 INSTRUCCIONES DE USO'], // Fila 2  
        ['1. Complete los datos a partir de la fila 12'], 
        ['2. Campos obligatorios: Número y Sexo'],
        ['3. Formato de fecha: dd/mm/aaaa (Ejemplo: 15/01/2020)'],
        ['4. Estados productivos disponibles:'],
        ['   • VP = Vaca Parida  |  VS = Vaca Seca  |  TR = Toro'],
        ['   • NV = Novilla de vientre  |  HL = Hembra de levante'],
        ['   • ML = Macho de levante  |  CH = Cría Hembra  |  CM = Cría Macho'],
        ['5. Para madre/padre: ingrese el código del animal ya registrado'],
        [''], // Fila 11
        // Fila 12: Encabezados
        ['Número', 'Nombre', 'Sexo', 'Fecha .Nto', 'Raza', 'Estado productivo', '# Madre', '# Padre', 'Hierro', 'Color'],
        ['001', 'Vaca Ejemplo', 'H', '15/01/2020', 'Holstein', 'VP', '', '', 'MF', 'Bayo claro'],
        ['002', 'Toro Padre', 'M', '20/05/2018', 'Brahman', 'TR', '', '', '456', 'Rojo'],
    ];
}

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Moollish Logo');
        $drawing->setDescription('Logo Moollish');
        $drawing->setPath(public_path('img/logo-letra1.png'));
        $drawing->setHeight(100);
        $drawing->setCoordinates('J2');
        
        return $drawing;
    }

    public function styles(Worksheet $sheet)
    {
        // Título (Fila 1)
        $sheet->mergeCells('A1:J1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'e49b39']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(35);

        // Instrucciones título (Fila 2)
        $sheet->mergeCells('A2:J2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFF4E6']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        // Contenido instrucciones (Filas 3-10)
        for ($i = 3; $i <= 10; $i++) {
            $sheet->mergeCells("A{$i}:J{$i}");
            $sheet->getStyle("A{$i}")->applyFromArray([
                'font' => ['size' => 10, 'color' => ['rgb' => '333333']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFBF5']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'vertical' => Alignment::VERTICAL_CENTER],
            ]);
        }

        // Fila 11 (separador con color instrucciones)
        $sheet->mergeCells('A11:J11');
        $sheet->getStyle('A11')->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFBF5']],
        ]);

        // Encabezados (Fila 12)
        $sheet->getStyle('A12:J12')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'e49b39']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ]);
        $sheet->getRowDimension(12)->setRowHeight(25);

        // Ejemplos y bordes
        $sheet->getStyle('A13:J100')->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);

        // Lista desplegable: Sexo (columna C, desde fila 13)
        for ($row = 13; $row <= 100; $row++) {
            $validation = $sheet->getCell("C{$row}")->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setErrorTitle('Valor inválido');
            $validation->setError('Solo puede seleccionar M o H de la lista');
            $validation->setShowDropDown(true);
            $validation->setFormula1('"M,H"');
        }

        // Lista desplegable: Estado productivo (columna F)
        for ($row = 13; $row <= 100; $row++) {
            $validation = $sheet->getCell("F{$row}")->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(true);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setErrorTitle('Valor inválido');
            $validation->setError('Solo puede seleccionar VP, VS, TR, NV, HL, ML, CH o CM de la lista');
            $validation->setShowDropDown(true);
            $validation->setFormula1('"VP,VS,TR,NV,HL,ML,CH,CM"');
        }

        // Anchos columnas
        $sheet->getColumnDimension('A')->setWidth(12);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(8);
        $sheet->getColumnDimension('D')->setWidth(12);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(12);
        $sheet->getColumnDimension('H')->setWidth(12);
        $sheet->getColumnDimension('I')->setWidth(10);
        $sheet->getColumnDimension('J')->setWidth(15);

        return [];
    }

    public function title(): string
    {
        return 'Animales Moollish';
    }
}