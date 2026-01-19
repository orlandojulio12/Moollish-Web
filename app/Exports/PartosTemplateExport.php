<?php

namespace App\Exports;

use App\Models\Animal;
use App\Models\EstadoProductivo;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class PartosTemplateExport implements WithMultipleSheets
{
    protected $predio_id;

    public function __construct($predio_id)
    {
        $this->predio_id = $predio_id;
    }

    public function sheets(): array
    {
        return [
            new PartosMainSheet($this->predio_id),
            new PartosListasSheet($this->predio_id),
        ];
    }
}


// HOJA PRINCIPAL
class PartosMainSheet implements FromArray, WithStyles, WithTitle, WithDrawings
{
    protected $predio_id;

    public function __construct($predio_id)
    {
        $this->predio_id = $predio_id;
    }

    public function array(): array
    {
        // Obtener primera vaca y toro para ejemplos
        $primeraVaca = Animal::where('id_predio', $this->predio_id)
            ->where('sexo', 'hembra')
            ->where('estado_vida', 1)
            ->orderBy('codigo')
            ->first();

        $primerToro = Animal::where('id_predio', $this->predio_id)
            ->where('sexo', 'macho')
            ->where('estado_vida', 1)
            ->whereIn('estado_productivo_id', [
                EstadoProductivo::REPRODUCTOR_TORO,
                EstadoProductivo::MACHO_LEVANTE,
                EstadoProductivo::MACHO_CEBA,
            ])
            ->orderBy('codigo')
            ->first();

        $ejemploVaca = $primeraVaca ? ($primeraVaca->codigo . ($primeraVaca->nombre ? ' - ' . $primeraVaca->nombre : '')) : 'Ejemplo-001';
        $ejemploToro = $primerToro ? ($primerToro->codigo . ($primerToro->nombre ? ' - ' . $primerToro->nombre : '')) : 'Toro-001';

        return [
            ['PLANTILLA DE IMPORTACIÓN DE PARTOS - HISTORIAL COMPLETO'], // Fila 1
            ['📋 INSTRUCCIONES DE USO'], // Fila 2
            ['1. Complete los datos a partir de la fila 17'], // Fila 3
            ['2. Campos obligatorios: Código Madre (DEBE estar registrado), Código Cría, Sexo Cría, Fecha Parto'], // Fila 4
            ['3. Formato de fecha: dd/mm/aaaa (Ejemplo: 15/01/2020)'], // Fila 5
            ['4. ⚠️ IMPORTANTE: Código Madre debe seleccionarse del desplegable o escribir uno registrado'], // Fila 6
            ['5. Puede importar múltiples partos de la misma vaca (historial completo)'], // Fila 7
            ['6. Código Padre: puede seleccionar del desplegable O escribir si es externo'], // Fila 8
            ['7. El sistema detecta automáticamente:'], // Fila 9
            ['   • Gemelar: 2 crías con misma madre y misma fecha'], // Fila 10
            ['   • Trillizo: 3 crías con misma madre y misma fecha'], // Fila 11
            ['8. Tipo Evento: SOLO usar si es Aborto o Muerte Fetal (dejar vacío para partos normales)'], // Fila 12
            ['9. Para Aborto/Muerte Fetal: dejar vacío Código Cría y Sexo Cría'], // Fila 13
            ['10. Estado actual: indica si la cría está o no en el predio actualmente'], // Fila 14
            [''], // Fila 15 - SEPARADOR VACÍO
            // Encabezados - Fila 16
            ['Código Madre', 'Código Cría', 'Nombre Cría', 'Sexo Cría', 'Fecha Parto', 'Código Padre', 'Raza Cría', 'Hierro', 'Color', 'Estado actual', 'Tipo Evento', 'Observaciones'],
            // Ejemplos - Fila 17 y 18
            [$ejemploVaca, '010-CRIA1', 'Ternero1', 'H', '15/01/2020', $ejemploToro, 'Holstein', 'MF', 'Bayo claro', 'Está en el predio', '', 'Parto normal'],
            [$ejemploVaca, '020-CRIA2', 'Ternero2', 'M', '10/03/2021', $ejemploToro, 'Holstein', 'MF', 'Negro', 'No está en el predio', '', 'Segundo parto misma vaca'],
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Moollish Logo');
        $drawing->setPath(public_path('img/logo-letra1.png'));
        $drawing->setHeight(100);
        $drawing->setCoordinates('K2');
        return file_exists(public_path('img/logo-letra1.png')) ? $drawing : [];
    }

   public function styles(Worksheet $sheet)
{
    // Título (Fila 1)
    $sheet->mergeCells('A1:L1');
    $sheet->getStyle('A1')->applyFromArray([
        'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'e49b39']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
    ]);
    $sheet->getRowDimension(1)->setRowHeight(35);

    // Instrucciones título (Fila 2)
    $sheet->mergeCells('A2:L2');
    $sheet->getStyle('A2')->applyFromArray([
        'font' => ['bold' => true, 'size' => 12],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFF4E6']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
    ]);

    // Contenido instrucciones (filas 3-14)
    for ($i = 3; $i <= 14; $i++) {
        $sheet->mergeCells("A{$i}:L{$i}");
        $sheet->getStyle("A{$i}")->applyFromArray([
            'font' => ['size' => 10, 'color' => ['rgb' => '333333']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFBF5']],
        ]);
    }

    // Fila 15 (separador vacío)
    $sheet->mergeCells('A15:L15');
    $sheet->getStyle('A15')->applyFromArray([
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFBF5']],
    ]);

    // Encabezados (Fila 16)
    $sheet->getStyle('A16:L16')->applyFromArray([
        'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'FFFFFF']],
        'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'e49b39']],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
    ]);
    $sheet->getRowDimension(16)->setRowHeight(25);

    // Datos (desde fila 17)
    $sheet->getStyle('A17:L100')->applyFromArray([
        'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
    ]);

    // Contar cuántas vacas y toros hay
    $totalVacas = Animal::where('id_predio', $this->predio_id)
        ->where('sexo', 'hembra')
        ->where('estado_vida', 1)
        ->count();

    $totalToros = Animal::where('id_predio', $this->predio_id)
        ->where('sexo', 'macho')
        ->where('estado_vida', 1)
        ->whereIn('estado_productivo_id', [
            EstadoProductivo::REPRODUCTOR_TORO,
            EstadoProductivo::MACHO_LEVANTE,
            EstadoProductivo::MACHO_CEBA,
        ])
        ->count();

    // ✅ VALIDACIÓN ESTRICTA: Código Madre (columna A)
    if ($totalVacas > 0) {
        for ($row = 17; $row <= 100; $row++) {
            $validation = $sheet->getCell("A{$row}")->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_STOP);
            $validation->setAllowBlank(false);
            $validation->setShowDropDown(true);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setPromptTitle('📝 Código de Vaca');
            $validation->setPrompt('Seleccione o escriba un código registrado en el predio.');
            $validation->setErrorTitle('❌ Código NO registrado');
            $validation->setError('El código de vaca NO existe en el predio. Debe estar registrado previamente.');
            $validation->setFormula1("Listas!\$A\$2:\$A\$" . ($totalVacas + 1));
        }
    }

    // ✅ Código Padre (columna F) - Permite externos
    if ($totalToros > 0) {
        for ($row = 17; $row <= 100; $row++) {
            $validation = $sheet->getCell("F{$row}")->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(true);
            $validation->setShowDropDown(true);
            $validation->setShowInputMessage(true);
            $validation->setPromptTitle('Sugerencia');
            $validation->setPrompt('Seleccione un toro del predio o escriba uno externo');
            $validation->setFormula1("Listas!\$B\$2:\$B\$" . ($totalToros + 1));
        }
    }

    // ✅ VALIDACIÓN ESTRICTA: Sexo Cría (columna D)
    for ($row = 17; $row <= 100; $row++) {
        $validation = $sheet->getCell("D{$row}")->getDataValidation();
        $validation->setType(DataValidation::TYPE_LIST);
        $validation->setErrorStyle(DataValidation::STYLE_STOP); // ❌ BLOQUEA
        $validation->setAllowBlank(true);
        $validation->setShowDropDown(true);
        $validation->setShowInputMessage(true); // ✅ AGREGADO
        $validation->setShowErrorMessage(true); // ✅ AGREGADO
        $validation->setPromptTitle('Sexo de la Cría');
        $validation->setPrompt('Seleccione M (Macho) o H (Hembra)');
        $validation->setErrorTitle('❌ Valor inválido');
        $validation->setError('Solo puede ser M (Macho) o H (Hembra)');
        $validation->setFormula1('"M,H"');
    }

    // ✅ VALIDACIÓN ESTRICTA: Estado actual (columna J)
    for ($row = 17; $row <= 100; $row++) {
        $validation = $sheet->getCell("J{$row}")->getDataValidation();
        $validation->setType(DataValidation::TYPE_LIST);
        $validation->setErrorStyle(DataValidation::STYLE_STOP); // ❌ BLOQUEA
        $validation->setAllowBlank(false);
        $validation->setShowDropDown(true);
        $validation->setShowInputMessage(true); // ✅ AGREGADO
        $validation->setShowErrorMessage(true); // ✅ AGREGADO
        $validation->setPromptTitle('Estado de la Cría');
        $validation->setPrompt('Indique si la cría está actualmente en el predio');
        $validation->setErrorTitle('❌ Valor inválido');
        $validation->setError('Solo: "Está en el predio" o "No está en el predio"');
        $validation->setFormula1('"Está en el predio,No está en el predio"');
    }

    // ✅ VALIDACIÓN ESTRICTA: Tipo Evento (columna K)
    for ($row = 17; $row <= 100; $row++) {
        $validation = $sheet->getCell("K{$row}")->getDataValidation();
        $validation->setType(DataValidation::TYPE_LIST);
        $validation->setErrorStyle(DataValidation::STYLE_STOP); // ❌ BLOQUEA
        $validation->setAllowBlank(true);
        $validation->setShowDropDown(true);
        $validation->setShowInputMessage(true); // ✅ AGREGADO
        $validation->setShowErrorMessage(true); // ✅ AGREGADO
        $validation->setPromptTitle('Tipo de Evento');
        $validation->setPrompt('Solo si es Aborto o Muerte Fetal. Deje vacío para partos normales.');
        $validation->setErrorTitle('❌ Valor inválido');
        $validation->setError('Solo: "Aborto" o "Muerte Fetal" (dejar vacío para partos normales)');
        $validation->setFormula1('"Aborto,Muerte Fetal"');
    }

    // Anchos de columnas
    $sheet->getColumnDimension('A')->setWidth(18);
    $sheet->getColumnDimension('B')->setWidth(15);
    $sheet->getColumnDimension('C')->setWidth(20);
    $sheet->getColumnDimension('D')->setWidth(10);
    $sheet->getColumnDimension('E')->setWidth(12);
    $sheet->getColumnDimension('F')->setWidth(18);
    $sheet->getColumnDimension('G')->setWidth(15);
    $sheet->getColumnDimension('H')->setWidth(10);
    $sheet->getColumnDimension('I')->setWidth(15);
    $sheet->getColumnDimension('J')->setWidth(20);
    $sheet->getColumnDimension('K')->setWidth(15);
    $sheet->getColumnDimension('L')->setWidth(25);

    return [];
}

    public function title(): string
    {
        return 'Partos Moollish';
    }
}

// HOJA OCULTA CON LISTAS COMPLETAS
class PartosListasSheet implements FromArray, WithTitle, WithStyles
{
    protected $predio_id;

    public function __construct($predio_id)
    {
        $this->predio_id = $predio_id;
    }

    public function array(): array
    {
        // Obtener TODAS las vacas
        $vacas = Animal::where('id_predio', $this->predio_id)
            ->where('sexo', 'hembra')
            ->where('estado_vida', 1)
            ->orderBy('codigo')
            ->get()
            ->map(function($vaca) {
                return [$vaca->codigo . ($vaca->nombre ? ' - ' . $vaca->nombre : '')];
            })
            ->toArray();

        // Obtener TODOS los toros
        $toros = Animal::where('id_predio', $this->predio_id)
            ->where('sexo', 'macho')
            ->where('estado_vida', 1)
            ->whereIn('estado_productivo_id', [
                EstadoProductivo::REPRODUCTOR_TORO,
                EstadoProductivo::MACHO_LEVANTE,
                EstadoProductivo::MACHO_CEBA,
            ])
            ->orderBy('codigo')
            ->get()
            ->map(function($toro) {
                return [$toro->codigo . ($toro->nombre ? ' - ' . $toro->nombre : '')];
            })
            ->toArray();

        // Hacer que ambas columnas tengan el mismo tamaño
        $maxRows = max(count($vacas), count($toros));
        $data = [['VACAS', 'TOROS']]; // Encabezados

        for ($i = 0; $i < $maxRows; $i++) {
            $data[] = [
                $vacas[$i][0] ?? '',
                $toros[$i][0] ?? ''
            ];
        }

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        // Ocultar la hoja
        $sheet->setSheetState(Worksheet::SHEETSTATE_HIDDEN);

        // Estilo a encabezados
        $sheet->getStyle('A1:B1')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'CCCCCC']],
        ]);

        return [];
    }

    public function title(): string
    {
        return 'Listas';
    }
}