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
use Maatwebsite\Excel\Concerns\WithTitle; // Agregar esta interfaz
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CensoExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new CensoBovinoExport(),             // Hoja para BOVINOS
            new CensoBufalinoExport(),           // Hoja para BUFALINOS
            new CensoPorcinoExport(),            // Hoja para PORCINOS
            new CensoEquidoExport(),             // Hoja para ÉQUIDOS
            new CensoOvinoCaprinoExport(),       // Hoja para OVINOS Y CAPRINOS
            new CensoOtrasEspecExport(),         // Hoja para OTRAS ESPECIES
            new CensoPezExport(),                // Hoja para PECES
            new CensoCructaceoExport(),          // Hoja para CRUSTÁCEOS
            new CensoAvesComercialesExport(),    // Hoja para AVES COMERCIALES
            new CensoAvesTraspatioExport(),      // Hoja para OTRAS AVES
            new CensoAbejasExport(),             // Hoja para ABEJAS
            new IdentificacionAnimalExport(),    // Hoja para IDENTIFICACIÓN ANIMAL
        ];
    }    
}


class CensoBovinoExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    /**
     * Retornar los datos de la tabla 'censo_bovinos' con relaciones
     */
    public function collection()
    {
        return CensoBovino::with(['predio'])->get();
    }

    /**
     * Mapeo de las columnas para que muestre los nombres en lugar de los IDs
     */
    public function map($censoBovino): array
    {
        return [
            $censoBovino->predio ? $censoBovino->predio->nombre_predio : 'Sin predio',  // Nombre del predio
            $censoBovino->men_3_meses_h,
            $censoBovino->tres_a_9_meses_h,
            $censoBovino->nueve_a_12_meses_h,
            $censoBovino->uno_a_2_años_h,
            $censoBovino->dos_a_3_años_h,
            $censoBovino->tres_a_5_años_h,
            $censoBovino->may_5_años_h,
            $censoBovino->total_hembras,
            $censoBovino->men_3_meses_m,
            $censoBovino->tres_a_9_meses_m,
            $censoBovino->nueve_a_12_meses_m,
            $censoBovino->uno_a_2_años_m,
            $censoBovino->dos_a_3_años_m,
            $censoBovino->may_3_años,
            $censoBovino->total_machos,
            $censoBovino->total_bovinos,
        ];
    }

    /**
     * Definir las cabeceras del archivo Excel
     */
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
        ];
    }

    /**
     * Aplicar estilos y espaciar las celdas
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo de cabeceras en negrita
        $sheet->getStyle('A1:P1')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        // Aumentar el ancho de las columnas
        foreach (range('A', 'P') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    /**
     * Definir el título de la hoja de Excel
     */
    public function title(): string
    {
        return 'BOVINOS'; // Nombre correcto de la hoja
    }
}

class CensoBufalinoExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    /**
     * Retornar los datos de la tabla 'censo_bufalinos' con relaciones
     */
    public function collection()
    {
        return CensoBufalino::with(['predio'])->get();
    }

    /**
     * Mapeo de las columnas para que muestre los nombres en lugar de los IDs
     */
    public function map($censoBufalino): array
    {
        return [
            $censoBufalino->predio ? $censoBufalino->predio->nombre_predio : 'Sin predio',  // Nombre del predio
            $censoBufalino->men_3_meses_h,
            $censoBufalino->tres_a_9_meses_h,
            $censoBufalino->nueve_a_12_meses_h,
            $censoBufalino->uno_a_2_años_h,
            $censoBufalino->dos_a_3_años_h,
            $censoBufalino->tres_a_5_años_h,
            $censoBufalino->may_5_años_h,
            $censoBufalino->total_hembras,
            $censoBufalino->men_3_meses_m,
            $censoBufalino->tres_a_9_meses_m,
            $censoBufalino->nueve_a_12_meses_m,
            $censoBufalino->uno_a_2_años_m,
            $censoBufalino->dos_a_3_años_m,
            $censoBufalino->may_3_años,
            $censoBufalino->total_machos,
            $censoBufalino->total_bufalinos,
        ];
    }

    /**
     * Definir las cabeceras del archivo Excel
     */
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
        ];
    }

    /**
     * Aplicar estilos y espaciar las celdas
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo de cabeceras en negrita
        $sheet->getStyle('A1:P1')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        // Aumentar el ancho de las columnas
        foreach (range('A', 'P') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    /**
     * Definir el título de la hoja de Excel
     */
    public function title(): string
    {
        return 'BUFALINOS'; // Nombre correcto de la hoja
    }
}

class CensoPorcinoExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    /**
     * Retornar los datos de la tabla 'censo_porcinos' con relaciones
     */
    public function collection()
    {
        return CensoPorcino::with(['predio'])->get();
    }

    /**
     * Mapeo de las columnas para que muestre los nombres en lugar de los IDs
     */
    public function map($censoPorcino): array
    {
        return [
            $censoPorcino->predio ? $censoPorcino->predio->nombre_predio : 'Sin predio',  // Nombre del predio
            $censoPorcino->lact_hast_30_dias,
            $censoPorcino->precebo_31_a_60_dias,
            $censoPorcino->lev_ceb_61_180_dias,
            $censoPorcino->reempl_men_8_meses_h,
            $censoPorcino->cria_men_8_meses_h,
            $censoPorcino->macho_reprod_men_6_meses,
            $censoPorcino->total_porcinos,
        ];
    }

    /**
     * Definir las cabeceras del archivo Excel
     */
    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Lactantes hasta 30 días',
            'Precebo (31 a 60 días)',
            'Levante y Cebo (61 a 180 días)',
            'Reemplazo menores de 8 meses (Hembras)',
            'Cría menores de 8 meses (Hembras)',
            'Machos reproductores menores de 6 meses',
            'Total Porcinos',
        ];
    }

    /**
     * Aplicar estilos y espaciar las celdas
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo de cabeceras en negrita
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        // Aumentar el ancho de las columnas
        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    /**
     * Definir el título de la hoja de Excel
     */
    public function title(): string
    {
        return 'PORCINOS';  // Nombre de la hoja para censo porcinos
    }
}

class CensoEquidoExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    /**
     * Retornar los datos de la tabla 'censo_equidos' con relaciones
     */
    public function collection()
    {
        return CensoEquido::with(['predio'])->get();
    }

    /**
     * Mapeo de las columnas para que muestre los nombres en lugar de los IDs
     */
    public function map($censoEquido): array
    {
        return [
            $censoEquido->predio ? $censoEquido->predio->nombre_predio : 'Sin predio',  // Nombre del predio
            $censoEquido->men_6_mese_caballar,
            $censoEquido->seis_12_meses_caballar,
            $censoEquido->may_1_año_caballar,
            $censoEquido->total_caballar,
            $censoEquido->men_6_mese_mular,
            $censoEquido->seis_12_meses_mular,
            $censoEquido->may_1_año_mular,
            $censoEquido->total_mular,
            $censoEquido->men_6_mese_asnal,
            $censoEquido->seis_12_meses_asnal,
            $censoEquido->may_1_año_asnal,
            $censoEquido->total_asnal,
            $censoEquido->total_equidos,
        ];
    }

    /**
     * Definir las cabeceras del archivo Excel
     */
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
        ];
    }

    /**
     * Aplicar estilos y espaciar las celdas
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo de cabeceras en negrita
        $sheet->getStyle('A1:N1')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        // Aumentar el ancho de las columnas
        foreach (range('A', 'N') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    /**
     * Definir el título de la hoja de Excel
     */
    public function title(): string
    {
        return 'ÉQUIDOS';  // Nombre de la hoja para censo équidos
    }
}

class CensoOvinoCaprinoExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    /**
     * Retornar los datos de la tabla 'censo_ovino_caprino' con relaciones
     */
    public function collection()
    {
        return CensoOvinoCaprino::with(['predio'])->get();
    }

    /**
     * Mapeo de las columnas para que muestre los nombres en lugar de los IDs
     */
    public function map($censoOvinoCaprino): array
    {
        return [
            $censoOvinoCaprino->predio ? $censoOvinoCaprino->predio->nombre_predio : 'Sin predio',  // Nombre del predio
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
        ];
    }

    /**
     * Definir las cabeceras del archivo Excel
     */
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
        ];
    }

    /**
     * Aplicar estilos y espaciar las celdas
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo de cabeceras en negrita
        $sheet->getStyle('A1:O1')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        // Aumentar el ancho de las columnas
        foreach (range('A', 'O') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    /**
     * Definir el título de la hoja de Excel
     */
    public function title(): string
    {
        return 'OVINOS Y CAPRINOS';  // Nombre de la hoja para "Ovino Caprino"
    }
}

class CensoOtrasEspecExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    /**
     * Retornar los datos de la tabla 'censo_otras_espec' con relaciones
     */
    public function collection()
    {
        return CensoOtrasEspec::with(['predio'])->get();
    }

    /**
     * Mapeo de las columnas para que muestre los nombres en lugar de los IDs
     */
    public function map($censoOtrasEspec): array
    {
        return [
            $censoOtrasEspec->predio ? $censoOtrasEspec->predio->nombre_predio : 'Sin predio',  // Nombre del predio
            $censoOtrasEspec->llamas,
            $censoOtrasEspec->alpacas,
            $censoOtrasEspec->avectruces,
            $censoOtrasEspec->otras,
            $censoOtrasEspec->cuantas_otras,
        ];
    }

    /**
     * Definir las cabeceras del archivo Excel
     */
    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Llamas',
            'Alpacas',
            'Avectruces',
            'Otras Especies',
            'Cantidad de Otras Especies',
        ];
    }

    /**
     * Aplicar estilos y espaciar las celdas
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo de cabeceras en negrita
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        // Aumentar el ancho de las columnas
        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    /**
     * Definir el título de la hoja de Excel
     */
    public function title(): string
    {
        return 'OTRAS ESPECIES';  // Nombre de la hoja para "Otras Especies"
    }
}

class CensoPezExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    /**
     * Retornar los datos de la tabla 'censo_peces' con relaciones
     */
    public function collection()
    {
        return CensoPez::with(['predio', 'tipoEspeciePez'])->get();
    }

    /**
     * Mapeo de las columnas para que muestre los nombres en lugar de los IDs
     */
    public function map($censoPez): array
    {
        return [
            $censoPez->predio ? $censoPez->predio->nombre_predio : 'Sin predio',  // Nombre del predio
            $censoPez->tipoEspeciePez ? $censoPez->tipoEspeciePez->nombre : 'Sin especie',  // Nombre de la especie de pez
            $censoPez->ovas,
            $censoPez->alevinos,
            $censoPez->engorde,
            $censoPez->reproductores,
            $censoPez->total_pez_especie,
            $censoPez->total_peces,
        ];
    }

    /**
     * Definir las cabeceras del archivo Excel
     */
    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Especie de Peces',
            'Ovas',
            'Alevinos',
            'Engorde',
            'Reproductores',
            'Total por Especie',
            'Total de Peces',
        ];
    }

    /**
     * Aplicar estilos y espaciar las celdas
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo de cabeceras en negrita
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        // Aumentar el ancho de las columnas
        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    /**
     * Definir el título de la hoja de Excel
     */
    public function title(): string
    {
        return 'PECES';  // Nombre de la hoja para "Censo de Peces"
    }
}

class CensoCructaceoExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    /**
     * Retornar los datos de la tabla 'censo_cructaceos' con relaciones
     */
    public function collection()
    {
        return CensoCructaceo::with(['predio', 'tipoEspecieCructaceo'])->get();
    }

    /**
     * Mapeo de las columnas para que muestre los nombres en lugar de los IDs
     */
    public function map($censoCructaceo): array
    {
        return [
            $censoCructaceo->predio ? $censoCructaceo->predio->nombre_predio : 'Sin predio',  // Nombre del predio
            $censoCructaceo->tipoEspecieCructaceo ? $censoCructaceo->tipoEspecieCructaceo->nombre : 'Sin especie',  // Nombre de la especie de crustáceo
            $censoCructaceo->nauplinos,
            $censoCructaceo->larvicultura,
            $censoCructaceo->engorde,
            $censoCructaceo->reproductores,
            $censoCructaceo->total_especie_cructacio,
            $censoCructaceo->total_cructaceos,
        ];
    }

    /**
     * Definir las cabeceras del archivo Excel
     */
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
        ];
    }

    /**
     * Aplicar estilos y espaciar las celdas
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo de cabeceras en negrita
        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        // Aumentar el ancho de las columnas
        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    /**
     * Definir el título de la hoja de Excel
     */
    public function title(): string
    {
        return 'CRUSTÁCEOS';  // Nombre de la hoja para "Censo de Crustáceos"
    }
}

class CensoAvesComercialesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    /**
     * Retornar los datos de la tabla 'censo_aves_comerciales' con relaciones
     */
    public function collection()
    {
        return CensoAvesComerciales::with(['predio', 'tipoAveComercial'])->get();
    }

    /**
     * Mapeo de las columnas para que muestre los nombres en lugar de los IDs
     */
    public function map($censoAvesComerciales): array
    {
        return [
            $censoAvesComerciales->predio ? $censoAvesComerciales->predio->nombre_predio : 'Sin predio',  // Nombre del predio
            $censoAvesComerciales->tipoAveComercial ? $censoAvesComerciales->tipoAveComercial->nombre : 'Sin tipo',  // Tipo de Ave Comercial
            $censoAvesComerciales->linea,
            $censoAvesComerciales->num_aves,
            $censoAvesComerciales->edad,
            $censoAvesComerciales->num_galones,
            $censoAvesComerciales->area_galones,
            $censoAvesComerciales->densidad,
            $censoAvesComerciales->tiemp_descan_lotes,
            $censoAvesComerciales->procedencia_aves,
        ];
    }

    /**
     * Definir las cabeceras del archivo Excel
     */
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
        ];
    }

    /**
     * Aplicar estilos y espaciar las celdas
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo de cabeceras en negrita
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        // Aumentar el ancho de las columnas
        foreach (range('A', 'J') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    /**
     * Definir el título de la hoja de Excel
     */
    public function title(): string
    {
        return 'AVES COMERCIALES';  // Nombre de la hoja para "Censo de Aves Comerciales"
    }
}

class CensoAvesTraspatioExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    /**
     * Retornar los datos de la tabla 'censo_aves_traspatio' con relaciones
     */
    public function collection()
    {
        return CensoAvesTraspatio::with(['predio', 'tipoAveTranspatio'])->get();
    }

    /**
     * Mapeo de las columnas para que muestre los nombres en lugar de los IDs
     */
    public function map($censoAvesTraspatio): array
    {
        return [
            $censoAvesTraspatio->predio ? $censoAvesTraspatio->predio->nombre_predio : 'Sin predio',  // Nombre del predio
            $censoAvesTraspatio->tipoAveTranspatio ? $censoAvesTraspatio->tipoAveTranspatio->nombre : 'Sin tipo',  // Tipo de Ave Traspatio
            $censoAvesTraspatio->num_aves,
            $censoAvesTraspatio->edad,
            $censoAvesTraspatio->precedencia_aves,
            $censoAvesTraspatio->observaciones,
        ];
    }

    /**
     * Definir las cabeceras del archivo Excel
     */
    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Tipo de Ave de Traspatio',
            'Número de Aves',
            'Edad',
            'Procedencia de Aves',
            'Observaciones',
        ];
    }

    /**
     * Aplicar estilos y espaciar las celdas
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo de cabeceras en negrita
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        // Aumentar el ancho de las columnas
        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    /**
     * Definir el título de la hoja de Excel
     */
    public function title(): string
    {
        return 'OTRAS AVES';  // Nombre de la hoja para "Censo de Aves de Traspatio"
    }
}

class CensoAbejasExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    /**
     * Retornar los datos de la tabla 'censo_abejas' con relaciones
     */
    public function collection()
    {
        return CensoAbejas::with(['predio', 'tipoAbejas'])->get();
    }

    /**
     * Mapeo de las columnas para que muestre los nombres en lugar de los IDs
     */
    public function map($censoAbejas): array
    {
        return [
            $censoAbejas->predio ? $censoAbejas->predio->nombre_predio : 'Sin predio',  // Nombre del predio
            $censoAbejas->tipoAbejas ? $censoAbejas->tipoAbejas->nombre : 'Sin tipo',  // Tipo de Abejas
            $censoAbejas->num_apiarios,
            $censoAbejas->num_colmenas,
            $censoAbejas->poblacion_estimada,
            $censoAbejas->realiz_trashumancia,
            $censoAbejas->nom_estable_destino,
            $censoAbejas->departamento,
            $censoAbejas->municipio,
        ];
    }

    /**
     * Definir las cabeceras del archivo Excel
     */
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
        ];
    }

    /**
     * Aplicar estilos y espaciar las celdas
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo de cabeceras en negrita
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        // Aumentar el ancho de las columnas
        foreach (range('A', 'I') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    /**
     * Definir el título de la hoja de Excel
     */
    public function title(): string
    {
        return 'ABEJAS';  // Nombre de la hoja para "Censo de Abejas"
    }
}

class IdentificacionAnimalExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    /**
     * Retornar los datos de la tabla 'identificacion_animal' con relaciones
     */
    public function collection()
    {
        return IdentificacionAnimal::with(['predio'])->get();
    }

    /**
     * Mapeo de las columnas para que muestre los nombres en lugar de los IDs
     */
    public function map($identificacionAnimal): array
    {
        return [
            $identificacionAnimal->predio ? $identificacionAnimal->predio->nombre_predio : 'Sin predio',  // Nombre del predio
            $identificacionAnimal->porcinos_con,
            $identificacionAnimal->porcinos_sin,
            $identificacionAnimal->total_porcinos,
            $identificacionAnimal->bovinos_con,
            $identificacionAnimal->bovinos_sin,
            $identificacionAnimal->total_bovinos,
            $identificacionAnimal->bufalinos_con,
            $identificacionAnimal->bufalinos_sin,
            $identificacionAnimal->total_bufalinos,
        ];
    }

    /**
     * Definir las cabeceras del archivo Excel
     */
    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Porcinos con identificación',
            'Porcinos sin identificación',
            'Total Porcinos',
            'Bovinos con identificación',
            'Bovinos sin identificación',
            'Total Bovinos',
            'Bufalinos con identificación',
            'Bufalinos sin identificación',
            'Total Bufalinos',
        ];
    }

    /**
     * Aplicar estilos y espaciar las celdas
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo de cabeceras en negrita
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        // Aumentar el ancho de las columnas
        foreach (range('A', 'J') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    /**
     * Definir el título de la hoja de Excel
     */
    public function title(): string
    {
        return 'IDENTIFICACIÓN ANIMAL';  // Nombre de la hoja para "Identificación Animal"
    }
}
