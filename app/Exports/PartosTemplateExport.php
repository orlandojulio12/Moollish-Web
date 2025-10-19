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

class PartosTemplateExport implements FromArray, WithStyles, WithTitle, WithDrawings
{
    public function array(): array
    {
        return [
            ['PLANTILLA DE IMPORTACIÓN DE PARTOS'],
            ['📋 INSTRUCCIONES DE USO'],
            ['1. Complete los datos a partir de la fila 13'],
            ['2. Campos obligatorios: Código Madre, Código Cría, Sexo Cría, Fecha Parto'],
            ['3. Formato de fecha: dd/mm/aaaa (Ejemplo: 15/01/2025)'],
            ['4. El sistema detecta automáticamente:'],
            ['   • Gemelar: 2 crías con misma madre y misma fecha'],
            ['   • Trillizo: 3 crías con misma madre y misma fecha'],
            ['5. Para Aborto/Muerte Fetal: dejar vacío Código Cría y Sexo Cría'],
            ['6. Código Padre es opcional'],
            ['7. El sistema valida IEP (Intervalo Entre Partos) automáticamente'],
            [''],
            // Encabezados
            ['Código Madre', 'Código Cría', 'Nombre Cría', 'Sexo Cría', 'Fecha Parto', 'Código Padre', 'Raza Cría', 'Hierro', 'Tipo Evento', 'Observaciones'],
            ['001-MADRE', '010-CRIA1', 'Ternero1', 'H', '15/01/2025', '002-TORO', 'Holstein', 'MF', '', 'Parto normal'],
            ['001-MADRE', '011-CRIA2', 'Ternero2', 'M', '15/01/2025', '002-TORO', 'Holstein', 'MF', '', 'Gemelar detectado auto'],
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Moollish Logo');
        $drawing->setPath(public_path('img/logo-letra1.png'));
        $drawing->setHeight(100);
        $drawing->setCoordinates('I2');
        return $drawing;
    }

    public function styles(Worksheet $sheet)
    {
        // Título
        $sheet->mergeCells('A1:J1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'e49b39']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(35);

        // Instrucciones
        $sheet->mergeCells('A2:J2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFF4E6']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
        ]);

        for ($i = 3; $i <= 11; $i++) {
            $sheet->mergeCells("A{$i}:J{$i}");
            $sheet->getStyle("A{$i}")->applyFromArray([
                'font' => ['size' => 10, 'color' => ['rgb' => '333333']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFBF5']],
            ]);
        }

        $sheet->mergeCells('A12:J12');
        $sheet->getStyle('A12')->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFBF5']],
        ]);

        // Encabezados
        $sheet->getStyle('A13:J13')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'e49b39']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
        $sheet->getRowDimension(13)->setRowHeight(25);

        // Datos
        $sheet->getStyle('A14:J100')->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Lista: Sexo Cría (columna D)
        for ($row = 14; $row <= 100; $row++) {
            $validation = $sheet->getCell("D{$row}")->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Valor inválido');
            $validation->setError('Solo M o H');
            $validation->setFormula1('"M,H"');
        }

        // Lista: Tipo Evento (columna I)
        for ($row = 14; $row <= 100; $row++) {
            $validation = $sheet->getCell("I{$row}")->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Valor inválido');
            $validation->setError('Solo: Aborto o Muerte Fetal');
            $validation->setFormula1('"Aborto,Muerte Fetal"');
        }

        // Anchos
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(15);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(10);
        $sheet->getColumnDimension('E')->setWidth(12);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(10);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(25);

        return [];
    }

    public function title(): string
    {
        return 'Partos Moollish';
    }
}