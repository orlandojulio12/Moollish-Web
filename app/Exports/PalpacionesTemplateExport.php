<?php

namespace App\Exports;

use App\Models\User;
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
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class PalpacionesTemplateExport implements FromArray, WithStyles, WithTitle, WithDrawings
{
    public function array(): array
    {
        // Obtener veterinarios (usuarios con id_rol = 4)
        $veterinarios = User::where('id_rol', 4)->pluck('name')->toArray();
        $veterinariosList = implode(',', $veterinarios);

        return [
            ['PLANTILLA DE IMPORTACIÓN DE PALPACIONES'],
            ['📋 INSTRUCCIONES DE USO'],
            ['1. Complete los datos a partir de la fila 13'],
            ['2. Campos obligatorios: Código Vaca, Fecha Palpación, Resultado, Veterinario'],
            ['3. Formato de fecha: dd/mm/aaaa (Ejemplo: 15/01/2025)'],
            ['4. Resultado debe ser: Preñada o Vacía (seleccione del desplegable)'],
            ['5. Si Resultado es Vacía, Diagnóstico es obligatorio (seleccione del desplegable: Vacía ciclando, Vacía estática, Vacia normal, Cuerpo Luteo ovario derecho, Cuerpo Luteo ovario izquierdo, Folículo ovario derecho, Folículo ovario izquierdo, Quistes, indantilismo genital)'],
            ['6. Días de Preñada (0-285) y Parto Proyectado (dd/mm/aaaa) son opcionales, solo para Preñada. Parto Proyectado será calculado automáticamente si se proporciona Días de Preñada.'],
            ['7. Veterinario debe ser un nombre existente del desplegable'],
            [''],
            [''],
            ['Código Vaca', 'Fecha Palpación', 'Resultado', 'Diagnóstico', 'Días de Preñada', 'Parto Proyectado', 'Veterinario'],
            ['001-MADRE', '15/01/2025', 'Preñada', '', '45', '01/11/2025', $veterinarios[0] ?? 'Vet Ejemplo'],
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Moollish Logo');
        $drawing->setPath(public_path('img/logo-letra1.png'));
        $drawing->setHeight(100);
        $drawing->setCoordinates('F2');
        return file_exists(public_path('img/logo-letra1.png')) ? $drawing : [];
    }

    public function styles(Worksheet $sheet)
    {
        // Título
        $sheet->mergeCells('A1:G1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'e49b39']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(35);

        // Instrucciones
        $sheet->mergeCells('A2:G2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFF4E6']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
        ]);

        for ($i = 3; $i <= 10; $i++) {
            $sheet->mergeCells("A{$i}:G{$i}");
            $sheet->getStyle("A{$i}")->applyFromArray([
                'font' => ['size' => 10, 'color' => ['rgb' => '333333']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFBF5']],
            ]);
        }

        $sheet->mergeCells('A11:G11');
        $sheet->getStyle('A11')->applyFromArray([
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFBF5']],
        ]);

        // Encabezados
        $sheet->getStyle('A12:G12')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'e49b39']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
        $sheet->getRowDimension(12)->setRowHeight(25);

        // Datos
        $sheet->getStyle('A13:G100')->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Dropdown: Resultado (columna C)
        for ($row = 13; $row <= 100; $row++) {
            $validation = $sheet->getCell("C{$row}")->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(false);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Valor inválido');
            $validation->setError('Solo Preñada o Vacía');
            $validation->setFormula1('"Preñada,Vacía"');
        }

        // Dropdown: Diagnóstico (columna D)
        $diagnosticos = 'Vacía ciclando,Vacía estática,Vacia normal,Cuerpo Luteo ovario derecho,Cuerpo Luteo ovario izquierdo,Folículo ovario derecho,Folículo ovario izquierdo,Quistes,indantilismo genital';
        for ($row = 13; $row <= 100; $row++) {
            $validation = $sheet->getCell("D{$row}")->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Valor inválido');
            $validation->setError('Seleccione un diagnóstico válido para Vacía');
            $validation->setFormula1('"'.$diagnosticos.'"');
        }

        // Dropdown: Veterinario (columna G)
        $veterinarios = User::where('id_rol', 4)->pluck('name')->toArray();
        $veterinariosList = implode(',', $veterinarios);
        for ($row = 13; $row <= 100; $row++) {
            $validation = $sheet->getCell("G{$row}")->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(false);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Veterinario inválido');
            $validation->setError('Seleccione un veterinario registrado');
            $validation->setFormula1('"'.$veterinariosList.'"');
        }

        // Anchos
        $sheet->getColumnDimension('A')->setWidth(15);
        $sheet->getColumnDimension('B')->setWidth(12);
        $sheet->getColumnDimension('C')->setWidth(12);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(12);
        $sheet->getColumnDimension('F')->setWidth(12);
        $sheet->getColumnDimension('G')->setWidth(20);

        $sheet->getStyle('A:A')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
        $sheet->getStyle('B:B')->getNumberFormat()->setFormatCode('dd/mm/yyyy');

        return [];
    }

    public function title(): string
    {
        return 'Palpaciones Moollish';
    }
}
