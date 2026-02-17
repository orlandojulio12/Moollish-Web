<?php

namespace App\Exports;

use App\Models\CensoBovino;
use App\Models\CensoBufalino;
use App\Models\CensoPorcino;
use App\Models\CensoEquido;
use App\Models\CensoOvinoCaprino;
use App\Models\CensoOtrasEspec;
use App\Models\CensoPez;
use App\Models\CensoCructaceo;
use App\Models\CensoAvesComerciales;
use App\Models\CensoAvesTraspatio;
use App\Models\CensoAbejas;
use App\Models\IdentificacionAnimal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CensoExport implements WithMultipleSheets
{
    protected $prediosFiltrados;

    public function __construct($prediosFiltrados = 'all')
    {
        $this->prediosFiltrados = $prediosFiltrados;
    }

    public function sheets(): array
    {
        return [
            new CensoBovinoExport($this->prediosFiltrados),
            new CensoBufalinoExport($this->prediosFiltrados),
            new CensoPorcinoExport($this->prediosFiltrados),
            new CensoEquidoExport($this->prediosFiltrados),
            new CensoOvinoCaprinoExport($this->prediosFiltrados),
            new CensoOtrasEspecExport($this->prediosFiltrados),
            new CensoPezExport($this->prediosFiltrados),
            new CensoCructaceoExport($this->prediosFiltrados),
            new CensoAvesComercialesExport($this->prediosFiltrados),
            new CensoAvesTraspatioExport($this->prediosFiltrados),
            new CensoAbejasExport($this->prediosFiltrados),
            new IdentificacionAnimalExport($this->prediosFiltrados),
        ];
    }    
}

// ==================== HOJA 1: BOVINOS ====================
class CensoBovinoExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    protected $prediosFiltrados;

    public function __construct($prediosFiltrados = 'all')
    {
        $this->prediosFiltrados = $prediosFiltrados;
    }

    public function collection()
    {
        $query = CensoBovino::with(['predio']);

        if ($this->prediosFiltrados !== 'all' && !empty($this->prediosFiltrados)) {
            $query->whereIn('id_predio', $this->prediosFiltrados);
        }

        return $query->orderBy('id_predio')->get();
    }

    public function map($censoBovino): array
    {
        return [
            $censoBovino->predio ? $censoBovino->predio->nombre_predio : 'Sin predio',
            // HEMBRAS
            $censoBovino->men_3_meses_h,
            $censoBovino->tres_a_9_meses_h,
            $censoBovino->nueve_a_12_meses_h,
            $censoBovino->uno_a_2_años_h,
            $censoBovino->dos_a_3_años_h,
            $censoBovino->tres_a_5_años_h,
            $censoBovino->may_5_años_h,
            $censoBovino->total_hembras,
            // MACHOS
            $censoBovino->men_3_meses_m,
            $censoBovino->tres_a_9_meses_m,
            $censoBovino->nueve_a_12_meses_m,
            $censoBovino->uno_a_2_años_m,
            $censoBovino->dos_a_3_años_m,
            $censoBovino->may_3_años,
            $censoBovino->total_machos,
            // TOTAL
            $censoBovino->total_bovinos,
            $censoBovino->created_at ? $censoBovino->created_at->format('Y-m-d H:i:s') : '',
            $censoBovino->updated_at ? $censoBovino->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }

    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Menores de 3 meses (Hembras)',
            '3 a 9 meses (Hembras)',
            '9 a 12 meses (Hembras)',
            '1 a 2 años (Hembras)',
            '2 a 3 años (Hembras)',
            '3 a 5 años (Hembras)',
            'Mayores de 5 años (Hembras)',
            'Total Hembras',
            'Menores de 3 meses (Machos)',
            '3 a 9 meses (Machos)',
            '9 a 12 meses (Machos)',
            '1 a 2 años (Machos)',
            '2 a 3 años (Machos)',
            'Mayores de 3 años (Machos)',
            'Total Machos',
            'Total Bovinos',
            'Fecha de Creación',
            'Fecha de Actualización',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:S1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '8B4513']]
        ]);

        foreach (range('A', 'S') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    public function title(): string
    {
        return 'BOVINOS';
    }
}

// ==================== HOJA 2: BUFALINOS ====================
class CensoBufalinoExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    protected $prediosFiltrados;

    public function __construct($prediosFiltrados = 'all')
    {
        $this->prediosFiltrados = $prediosFiltrados;
    }

    public function collection()
    {
        $query = CensoBufalino::with(['predio']);

        if ($this->prediosFiltrados !== 'all' && !empty($this->prediosFiltrados)) {
            $query->whereIn('id_predio', $this->prediosFiltrados);
        }

        return $query->orderBy('id_predio')->get();
    }

    public function map($censoBufalino): array
    {
        return [
            $censoBufalino->predio ? $censoBufalino->predio->nombre_predio : 'Sin predio',
            // HEMBRAS
            $censoBufalino->men_3_meses_h,
            $censoBufalino->tres_a_9_meses_h,
            $censoBufalino->nueve_a_12_meses_h,
            $censoBufalino->uno_a_2_años_h,
            $censoBufalino->dos_a_3_años_h,
            $censoBufalino->tres_a_5_años_h,
            $censoBufalino->may_5_años_h,
            $censoBufalino->total_hembras,
            // MACHOS
            $censoBufalino->men_3_meses_m,
            $censoBufalino->tres_a_9_meses_m,
            $censoBufalino->nueve_a_12_meses_m,
            $censoBufalino->uno_a_2_años_m,
            $censoBufalino->dos_a_3_años_m,
            $censoBufalino->may_3_años,
            $censoBufalino->total_machos,
            // TOTAL
            $censoBufalino->total_bufalinos,
            $censoBufalino->created_at ? $censoBufalino->created_at->format('Y-m-d H:i:s') : '',
            $censoBufalino->updated_at ? $censoBufalino->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }

    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Menores de 3 meses (Hembras)',
            '3 a 9 meses (Hembras)',
            '9 a 12 meses (Hembras)',
            '1 a 2 años (Hembras)',
            '2 a 3 años (Hembras)',
            '3 a 5 años (Hembras)',
            'Mayores de 5 años (Hembras)',
            'Total Hembras',
            'Menores de 3 meses (Machos)',
            '3 a 9 meses (Machos)',
            '9 a 12 meses (Machos)',
            '1 a 2 años (Machos)',
            '2 a 3 años (Machos)',
            'Mayores de 3 años (Machos)',
            'Total Machos',
            'Total Bufalinos',
            'Fecha de Creación',
            'Fecha de Actualización',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:S1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4A4A4A']]
        ]);

        foreach (range('A', 'S') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    public function title(): string
    {
        return 'BUFALINOS';
    }
}

// ==================== HOJA 3: PORCINOS ====================
class CensoPorcinoExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    protected $prediosFiltrados;

    public function __construct($prediosFiltrados = 'all')
    {
        $this->prediosFiltrados = $prediosFiltrados;
    }

    public function collection()
    {
        $query = CensoPorcino::with(['predio']);

        if ($this->prediosFiltrados !== 'all' && !empty($this->prediosFiltrados)) {
            $query->whereIn('id_predio', $this->prediosFiltrados);
        }

        return $query->orderBy('id_predio')->get();
    }

    public function map($censoPorcino): array
    {
        return [
            $censoPorcino->predio ? $censoPorcino->predio->nombre_predio : 'Sin predio',
            $censoPorcino->lact_hast_30_dias,
            $censoPorcino->precebo_31_a_60_dias,
            $censoPorcino->lev_ceb_61_180_dias,
            $censoPorcino->reempl_men_8_meses_h,
            $censoPorcino->cria_men_8_meses_h,
            $censoPorcino->macho_reprod_men_6_meses,
            $censoPorcino->total_porcinos,
            $censoPorcino->created_at ? $censoPorcino->created_at->format('Y-m-d H:i:s') : '',
            $censoPorcino->updated_at ? $censoPorcino->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }

    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Lactancia (hasta 30 días)',
            'Precebo (31 a 60 días)',
            'Levante/Ceba (61 a 180 días)',
            'Reemplazo (menores de 8 meses - Hembras)',
            'Cría (menores de 8 meses - Hembras)',
            'Macho Reproductor (menores de 6 meses)',
            'Total Porcinos',
            'Fecha de Creación',
            'Fecha de Actualización',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FF69B4']]
        ]);

        foreach (range('A', 'J') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    public function title(): string
    {
        return 'PORCINOS';
    }
}

// ==================== HOJA 4: ÉQUIDOS ====================
class CensoEquidoExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    protected $prediosFiltrados;

    public function __construct($prediosFiltrados = 'all')
    {
        $this->prediosFiltrados = $prediosFiltrados;
    }

    public function collection()
    {
        $query = CensoEquido::with(['predio']);

        if ($this->prediosFiltrados !== 'all' && !empty($this->prediosFiltrados)) {
            $query->whereIn('id_predio', $this->prediosFiltrados);
        }

        return $query->orderBy('id_predio')->get();
    }

    public function map($censoEquido): array
    {
        return [
            $censoEquido->predio ? $censoEquido->predio->nombre_predio : 'Sin predio',
            // CABALLARES
            $censoEquido->men_6_mese_caballar,
            $censoEquido->seis_12_meses_caballar,
            $censoEquido->may_1_año_caballar,
            $censoEquido->total_caballar,
            // MULARES
            $censoEquido->men_6_mese_mular,
            $censoEquido->seis_12_meses_mular,
            $censoEquido->may_1_año_mular,
            $censoEquido->total_mular,
            // ASNALES
            $censoEquido->men_6_mese_asnal,
            $censoEquido->seis_12_meses_asnal,
            $censoEquido->may_1_año_asnal,
            $censoEquido->total_asnal,
            // TOTAL
            $censoEquido->total_equidos,
            $censoEquido->created_at ? $censoEquido->created_at->format('Y-m-d H:i:s') : '',
            $censoEquido->updated_at ? $censoEquido->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }

    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Menores de 6 meses (Caballar)',
            '6 a 12 meses (Caballar)',
            'Mayores de 1 año (Caballar)',
            'Total Caballar',
            'Menores de 6 meses (Mular)',
            '6 a 12 meses (Mular)',
            'Mayores de 1 año (Mular)',
            'Total Mular',
            'Menores de 6 meses (Asnal)',
            '6 a 12 meses (Asnal)',
            'Mayores de 1 año (Asnal)',
            'Total Asnal',
            'Total Équidos',
            'Fecha de Creación',
            'Fecha de Actualización',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:P1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '8B7355']]
        ]);

        foreach (range('A', 'P') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    public function title(): string
    {
        return 'ÉQUIDOS';
    }
}

// ==================== HOJA 5: OVINOS Y CAPRINOS ====================
class CensoOvinoCaprinoExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    protected $prediosFiltrados;

    public function __construct($prediosFiltrados = 'all')
    {
        $this->prediosFiltrados = $prediosFiltrados;
    }

    public function collection()
    {
        $query = CensoOvinoCaprino::with(['predio']);

        if ($this->prediosFiltrados !== 'all' && !empty($this->prediosFiltrados)) {
            $query->whereIn('id_predio', $this->prediosFiltrados);
        }

        return $query->orderBy('id_predio')->get();
    }

    public function map($censoOvinoCaprino): array
    {
        return [
            $censoOvinoCaprino->predio ? $censoOvinoCaprino->predio->nombre_predio : 'Sin predio',
            $censoOvinoCaprino->men_6_meses_h_ovi,
            $censoOvinoCaprino->may_6_meses_h_ovi,
            $censoOvinoCaprino->total_hembras_ovinas,
            $censoOvinoCaprino->men_6_meses_m_ovi,
            $censoOvinoCaprino->may_6_meses_m_ovi,
            $censoOvinoCaprino->total_machos_ovi,
            $censoOvinoCaprino->total_ovinos,
            $censoOvinoCaprino->men_6_meses_h_capri,
            $censoOvinoCaprino->may_6_meses_h_capri,
            $censoOvinoCaprino->total_hembras_capri,
            $censoOvinoCaprino->men_6_meses_m_capri,
            $censoOvinoCaprino->may_6_meses_m_capri,
            $censoOvinoCaprino->total_machos_capri,
            $censoOvinoCaprino->total_caprinos,
            $censoOvinoCaprino->created_at ? $censoOvinoCaprino->created_at->format('Y-m-d H:i:s') : '',
            $censoOvinoCaprino->updated_at ? $censoOvinoCaprino->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }

    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Menores de 6 meses (Hembras Ovinas)',
            'Mayores de 6 meses (Hembras Ovinas)',
            'Total Hembras Ovinas',
            'Menores de 6 meses (Machos Ovinos)',
            'Mayores de 6 meses (Machos Ovinos)',
            'Total Machos Ovinos',
            'Total Ovinos',
            'Menores de 6 meses (Hembras Caprinas)',
            'Mayores de 6 meses (Hembras Caprinas)',
            'Total Hembras Caprinas',
            'Menores de 6 meses (Machos Caprinos)',
            'Mayores de 6 meses (Machos Caprinos)',
            'Total Machos Caprinos',
            'Total Caprinos',
            'Fecha de Creación',
            'Fecha de Actualización',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:Q1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'D2691E']]
        ]);

        foreach (range('A', 'Q') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    public function title(): string
    {
        return 'OVINOS Y CAPRINOS';
    }
}

// ==================== HOJA 6: OTRAS ESPECIES ====================
class CensoOtrasEspecExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    protected $prediosFiltrados;

    public function __construct($prediosFiltrados = 'all')
    {
        $this->prediosFiltrados = $prediosFiltrados;
    }

    public function collection()
    {
        $query = CensoOtrasEspec::with(['predio']);

        if ($this->prediosFiltrados !== 'all' && !empty($this->prediosFiltrados)) {
            $query->whereIn('id_predio', $this->prediosFiltrados);
        }

        return $query->orderBy('id_predio')->get();
    }

    public function map($censoOtrasEspec): array
    {
        return [
            $censoOtrasEspec->predio ? $censoOtrasEspec->predio->nombre_predio : 'Sin predio',
            $censoOtrasEspec->llamas,
            $censoOtrasEspec->alpacas,
            $censoOtrasEspec->avectruces,
            $censoOtrasEspec->otras,
            $censoOtrasEspec->cuantas_otras,
            $censoOtrasEspec->created_at ? $censoOtrasEspec->created_at->format('Y-m-d H:i:s') : '',
            $censoOtrasEspec->updated_at ? $censoOtrasEspec->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }

    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Llamas',
            'Alpacas',
            'Avestruces',
            'Otras Especies',
            'Cantidad de Otras Especies',
            'Fecha de Creación',
            'Fecha de Actualización',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '9370DB']]
        ]);

        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    public function title(): string
    {
        return 'OTRAS ESPECIES';
    }
}

// ==================== HOJA 7: PECES ====================
class CensoPezExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    protected $prediosFiltrados;

    public function __construct($prediosFiltrados = 'all')
    {
        $this->prediosFiltrados = $prediosFiltrados;
    }

    public function collection()
    {
        $query = CensoPez::with(['predio', 'tipoEspeciePez']);

        if ($this->prediosFiltrados !== 'all' && !empty($this->prediosFiltrados)) {
            $query->whereIn('id_predio', $this->prediosFiltrados);
        }

        return $query->orderBy('id_predio')->get();
    }

    public function map($censoPez): array
    {
        return [
            $censoPez->predio ? $censoPez->predio->nombre_predio : 'Sin predio',
            $censoPez->tipoEspeciePez ? $censoPez->tipoEspeciePez->nombre : 'Sin especie',
            $censoPez->alevinos,
            $censoPez->juveniles,
            $censoPez->adultos,
            $censoPez->reproductores,
            $censoPez->total_especie_pez,
            $censoPez->total_peces,
            $censoPez->created_at ? $censoPez->created_at->format('Y-m-d H:i:s') : '',
            $censoPez->updated_at ? $censoPez->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }

    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Especie de Pez',
            'Alevinos',
            'Juveniles',
            'Adultos',
            'Reproductores',
            'Total por Especie',
            'Total de Peces',
            'Fecha de Creación',
            'Fecha de Actualización',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4169E1']]
        ]);

        foreach (range('A', 'J') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    public function title(): string
    {
        return 'PECES';
    }
}

// ==================== HOJA 8: CRUSTÁCEOS ====================
class CensoCructaceoExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    protected $prediosFiltrados;

    public function __construct($prediosFiltrados = 'all')
    {
        $this->prediosFiltrados = $prediosFiltrados;
    }

    public function collection()
    {
        $query = CensoCructaceo::with(['predio', 'tipoEspecieCructaceo']);

        if ($this->prediosFiltrados !== 'all' && !empty($this->prediosFiltrados)) {
            $query->whereIn('id_predio', $this->prediosFiltrados);
        }

        return $query->orderBy('id_predio')->get();
    }

    public function map($censoCructaceo): array
    {
        return [
            $censoCructaceo->predio ? $censoCructaceo->predio->nombre_predio : 'Sin predio',
            $censoCructaceo->tipoEspecieCructaceo ? $censoCructaceo->tipoEspecieCructaceo->nombre : 'Sin especie',
            $censoCructaceo->nauplinos,
            $censoCructaceo->larvicultura,
            $censoCructaceo->engorde,
            $censoCructaceo->reproductores,
            $censoCructaceo->total_especie_cructacio,
            $censoCructaceo->total_cructaceos,
            $censoCructaceo->created_at ? $censoCructaceo->created_at->format('Y-m-d H:i:s') : '',
            $censoCructaceo->updated_at ? $censoCructaceo->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }

    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Especie de Crustáceos',
            'Nauplinos',
            'Larvicultura',
            'Engorde',
            'Reproductores',
            'Total por Especie',
            'Total de Crustáceos',
            'Fecha de Creación',
            'Fecha de Actualización',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FF6347']]
        ]);

        foreach (range('A', 'J') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    public function title(): string
    {
        return 'CRUSTÁCEOS';
    }
}

// ==================== HOJA 9: AVES COMERCIALES ====================
class CensoAvesComercialesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    protected $prediosFiltrados;

    public function __construct($prediosFiltrados = 'all')
    {
        $this->prediosFiltrados = $prediosFiltrados;
    }

    public function collection()
    {
        $query = CensoAvesComerciales::with(['predio', 'tipoAveComercial']);

        if ($this->prediosFiltrados !== 'all' && !empty($this->prediosFiltrados)) {
            $query->whereIn('id_predio', $this->prediosFiltrados);
        }

        return $query->orderBy('id_predio')->get();
    }

    public function map($censoAvesComerciales): array
    {
        return [
            $censoAvesComerciales->predio ? $censoAvesComerciales->predio->nombre_predio : 'Sin predio',
            $censoAvesComerciales->tipoAveComercial ? $censoAvesComerciales->tipoAveComercial->nombre : 'Sin tipo',
            $censoAvesComerciales->linea,
            $censoAvesComerciales->num_aves,
            $censoAvesComerciales->edad,
            $censoAvesComerciales->num_galones,
            $censoAvesComerciales->area_galones,
            $censoAvesComerciales->densidad,
            $censoAvesComerciales->tiemp_descan_lotes,
            $censoAvesComerciales->procedencia_aves,
            $censoAvesComerciales->created_at ? $censoAvesComerciales->created_at->format('Y-m-d H:i:s') : '',
            $censoAvesComerciales->updated_at ? $censoAvesComerciales->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }

    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Tipo de Ave Comercial',
            'Línea',
            'Número de Aves',
            'Edad',
            'Número de Galpones',
            'Área de Galpones',
            'Densidad',
            'Tiempo de Descanso de Lotes',
            'Procedencia de Aves',
            'Fecha de Creación',
            'Fecha de Actualización',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFD700']]
        ]);

        foreach (range('A', 'L') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    public function title(): string
    {
        return 'AVES COMERCIALES';
    }
}

// ==================== HOJA 10: AVES DE TRASPATIO ====================
class CensoAvesTraspatioExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    protected $prediosFiltrados;

    public function __construct($prediosFiltrados = 'all')
    {
        $this->prediosFiltrados = $prediosFiltrados;
    }

    public function collection()
    {
        $query = CensoAvesTraspatio::with(['predio', 'tipoAveTranspatio']);

        if ($this->prediosFiltrados !== 'all' && !empty($this->prediosFiltrados)) {
            $query->whereIn('id_predio', $this->prediosFiltrados);
        }

        return $query->orderBy('id_predio')->get();
    }

    public function map($censoAvesTraspatio): array
    {
        return [
            $censoAvesTraspatio->predio ? $censoAvesTraspatio->predio->nombre_predio : 'Sin predio',
            $censoAvesTraspatio->tipoAveTranspatio ? $censoAvesTraspatio->tipoAveTranspatio->nombre : 'Sin tipo',
            $censoAvesTraspatio->pollos_pollas,
            $censoAvesTraspatio->gallinas_ponedoras,
            $censoAvesTraspatio->gallos,
            $censoAvesTraspatio->total_aves_traspatio,
            $censoAvesTraspatio->created_at ? $censoAvesTraspatio->created_at->format('Y-m-d H:i:s') : '',
            $censoAvesTraspatio->updated_at ? $censoAvesTraspatio->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }

    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Tipo de Ave de Traspatio',
            'Pollos/Pollas',
            'Gallinas Ponedoras',
            'Gallos',
            'Total Aves de Traspatio',
            'Fecha de Creación',
            'Fecha de Actualización',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFA500']]
        ]);

        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    public function title(): string
    {
        return 'AVES DE TRASPATIO';
    }
}

// ==================== HOJA 11: ABEJAS ====================
class CensoAbejasExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    protected $prediosFiltrados;

    public function __construct($prediosFiltrados = 'all')
    {
        $this->prediosFiltrados = $prediosFiltrados;
    }

    public function collection()
    {
        $query = CensoAbejas::with(['predio', 'tipoAbejas']);

        if ($this->prediosFiltrados !== 'all' && !empty($this->prediosFiltrados)) {
            $query->whereIn('id_predio', $this->prediosFiltrados);
        }

        return $query->orderBy('id_predio')->get();
    }

    public function map($censoAbejas): array
    {
        return [
            $censoAbejas->predio ? $censoAbejas->predio->nombre_predio : 'Sin predio',
            $censoAbejas->tipoAbejas ? $censoAbejas->tipoAbejas->nombre : 'Sin tipo',
            $censoAbejas->num_apiarios,
            $censoAbejas->num_colmenas,
            $censoAbejas->poblacion_estimada,
            $censoAbejas->realiz_trashumancia,
            $censoAbejas->nom_estable_destino,
            $censoAbejas->departamento,
            $censoAbejas->municipio,
            $censoAbejas->created_at ? $censoAbejas->created_at->format('Y-m-d H:i:s') : '',
            $censoAbejas->updated_at ? $censoAbejas->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }

    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Tipo de Abejas',
            'Número de Apiarios',
            'Número de Colmenas',
            'Población Estimada',
            'Realiza Trashumancia',
            'Nombre del Establecimiento Destino',
            'Departamento',
            'Municipio',
            'Fecha de Creación',
            'Fecha de Actualización',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => '000000']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFF00']]
        ]);

        foreach (range('A', 'K') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    public function title(): string
    {
        return 'ABEJAS';
    }
}

// ==================== HOJA 12: IDENTIFICACIÓN ANIMAL ====================
class IdentificacionAnimalExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    protected $prediosFiltrados;

    public function __construct($prediosFiltrados = 'all')
    {
        $this->prediosFiltrados = $prediosFiltrados;
    }

    public function collection()
    {
        $query = IdentificacionAnimal::with(['predio']);

        if ($this->prediosFiltrados !== 'all' && !empty($this->prediosFiltrados)) {
            $query->whereIn('id_predio', $this->prediosFiltrados);
        }

        return $query->orderBy('id_predio')->get();
    }

    public function map($identificacionAnimal): array
    {
        return [
            $identificacionAnimal->predio ? $identificacionAnimal->predio->nombre_predio : 'Sin predio',
            $identificacionAnimal->especie,
            $identificacionAnimal->total_animales,
            $identificacionAnimal->total_identificados,
            $identificacionAnimal->tipo_identificacion,
            $identificacionAnimal->created_at ? $identificacionAnimal->created_at->format('Y-m-d H:i:s') : '',
            $identificacionAnimal->updated_at ? $identificacionAnimal->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }

    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Especie',
            'Total de Animales',
            'Total Identificados',
            'Tipo de Identificación',
            'Fecha de Creación',
            'Fecha de Actualización',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '2E8B57']]
        ]);

        foreach (range('A', 'G') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    public function title(): string
    {
        return 'IDENTIFICACIÓN ANIMAL';
    }
}