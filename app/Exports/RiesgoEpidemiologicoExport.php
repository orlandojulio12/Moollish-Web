<?php

namespace App\Exports;

use App\Models\Tipo_explotacion;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Models\InforEpidemiologica;
use App\Models\CarazterizacionRiesgo;
use App\Models\VisitaPrediosRiesgo;

class RiesgoEpidemiologicoExport implements WithMultipleSheets
{
    /**
     * Retornar las hojas de cálculo
     *
     * @return array
     */
    public function sheets(): array
    {
        return [
            new TipoExplotacionExport(),  // La hoja para Tipo de Explotación
            new InforEpidemiologicaExport(),  // La hoja para Información Epidemiológica
            new CaracterizacionRiesgoExport(),  // La hoja para Caracterización de Riesgo
            new VisitaPrediosRiesgoExport(),  // La nueva hoja para Visita a Predios de Riesgo
        ];
    }
}

class TipoExplotacionExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    /**
     * Retornar los datos de la tabla 'tp_explotacion' con relaciones
     */
    public function collection()
    {
        return Tipo_explotacion::with('predio')->get();
    }

    /**
     * Mapeo de las columnas para que muestre los nombres en lugar de los IDs
     */
    public function map($tipoExplotacion): array
    {
        return [
            $tipoExplotacion->predio ? $tipoExplotacion->predio->nombre_predio : 'Sin predio',  // Nombre del predio
            $tipoExplotacion->bovinos,
            $tipoExplotacion->bufalinos,
            $tipoExplotacion->porcinos,
            $tipoExplotacion->equinos,
            $tipoExplotacion->ovinos,
            $tipoExplotacion->caprinos,
            $tipoExplotacion->aves_corral,
            $tipoExplotacion->aves_no_corral,
            $tipoExplotacion->peces,
            $tipoExplotacion->crustaceos,
            $tipoExplotacion->sistem_acuaticos,
            $tipoExplotacion->apicolas,
            $tipoExplotacion->enferm_ovin_capri,
            $tipoExplotacion->enferm_ovin_capri_cual,
            $tipoExplotacion->mortali_x_enfermedad,
            $tipoExplotacion->mortali_x_enfermedad_cual,
            $tipoExplotacion->pre_apic_produc_explot,
        ];
    }

    /**
     * Definir las cabeceras del archivo Excel
     */
    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Bovinos',
            'Bufalinos',
            'Porcinos',
            'Equinos',
            'Ovinos',
            'Caprinos',
            'Aves de Corral',
            'Aves No de Corral',
            'Peces',
            'Crustáceos',
            'Sistemas Acuáticos',
            'Apícolas',
            'Enfermedades Ovino-Caprinas',
            'Cuáles Enfermedades Ovino-Caprinas',
            'Mortalidad por Enfermedades',
            'Cuáles Enfermedades Causaron Mortalidad',
            'Apicultura y Producción de Explotación',
        ];
    }

    /**
     * Aplicar estilos y espaciar las celdas
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo de cabeceras en negrita
        $sheet->getStyle('A1:R1')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        // Aumentar el ancho de las columnas
        foreach (range('A', 'R') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    /**
     * Definir el título de la hoja
     */
    public function title(): string
    {
        return 'TIPO EXPLOTACIÓN';
    }
}

class InforEpidemiologicaExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    /**
     * Retornar los datos de la tabla 'infor_epidemiologica' con relaciones
     */
    public function collection()
    {
        return InforEpidemiologica::with('predio')->get();
    }

    /**
     * Mapeo de las columnas para que muestre los nombres en lugar de los IDs
     */
    public function map($inforEpidemiologica): array
    {
        return [
            $inforEpidemiologica->predio ? $inforEpidemiologica->predio->nombre_predio : 'Sin predio',  // Nombre del predio
            $inforEpidemiologica->anim_enferm_control,
            $inforEpidemiologica->anim_enferm_control_cant,
            $inforEpidemiologica->cuadr_clinc_sospec,
            $inforEpidemiologica->especies_afectadas,
            $inforEpidemiologica->toma_muestra,
            $inforEpidemiologica->toma_muestra_tipos,
            $inforEpidemiologica->toma_muestra_numeros,
        ];
    }

    /**
     * Definir las cabeceras del archivo Excel
     */
    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Animales Enfermos Control',
            'Cantidad de Animales Controlados',
            'Cuadro Clínico Sospechoso',
            'Especies Afectadas',
            'Toma de Muestra',
            'Tipos de Muestra',
            'Número de Muestras',
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
     * Definir el título de la hoja
     */
    public function title(): string
    {
        return 'INFORMACIÓN EPIDEMIOLÓGICA';
    }
}

class CaracterizacionRiesgoExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    /**
     * Retornar los datos de la tabla 'caracterizacion_riesgo' con relaciones
     */
    public function collection()
    {
        return CarazterizacionRiesgo::with('predio')->get();
    }

    /**
     * Mapeo de las columnas para que muestre los nombres en lugar de los IDs
     */
    public function map($caracterizacionRiesgo): array
    {
        return [
            $caracterizacionRiesgo->predio ? $caracterizacionRiesgo->predio->nombre_predio : 'Sin predio',  // Nombre del predio
            $caracterizacionRiesgo->colinda_establecim_riesgo,
            $caracterizacionRiesgo->colinda_establecim_cual,
            $caracterizacionRiesgo->ubica_en_via,
            $caracterizacionRiesgo->alimen_animal,
            $caracterizacionRiesgo->alimen_animl_otro,
            $caracterizacionRiesgo->lavazas_desper_alimen_porc,
            $caracterizacionRiesgo->real_coccion_previa,
            $caracterizacionRiesgo->sacrif_anim_pred,
            $caracterizacionRiesgo->sacrif_anim_pred_periodic,
            $caracterizacionRiesgo->servic_reproduc,
            $caracterizacionRiesgo->servic_reproduc_otro,
            $caracterizacionRiesgo->num_trabajadores,
            $caracterizacionRiesgo->trabajan_otr_explotacion,
            $caracterizacionRiesgo->asistencia_tecnica,
            $caracterizacionRiesgo->asistencia_tecnica_frecuen,
            $caracterizacionRiesgo->atiend_otr_predi,
            $caracterizacionRiesgo->atiend_otr_predi_cual,
        ];
    }

    /**
     * Definir las cabeceras del archivo Excel
     */
    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Colinda con Establecimiento de Riesgo',
            '¿Cuál Establecimiento?',
            'Ubicación en Vía',
            'Alimento Animal',
            'Alimento Animal (Otro)',
            'Lavazas o Desperdicios Alimentarios (Porcinos)',
            'Realiza Cocción Previa',
            'Sacrificio de Animales en el Predio',
            'Periodicidad del Sacrificio',
            'Servicio de Reproducción',
            'Servicio de Reproducción (Otro)',
            'Número de Trabajadores',
            'Trabajan en Otra Explotación',
            'Asistencia Técnica',
            'Frecuencia de Asistencia Técnica',
            'Atiende Otros Predios',
            '¿Cuáles Predios?',
        ];
    }

    /**
     * Aplicar estilos y espaciar las celdas
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo de cabeceras en negrita
        $sheet->getStyle('A1:R1')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        // Aumentar el ancho de las columnas
        foreach (range('A', 'R') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    /**
     * Definir el título de la hoja
     */
    public function title(): string
    {
        return 'CARACTERIZACIÓN DE RIESGO';
    }
}

class VisitaPrediosRiesgoExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    /**
     * Retornar los datos de la tabla 'visita_predios_riesgo' con relaciones
     */
    public function collection()
    {
        return VisitaPrediosRiesgo::with('predio')->get();
    }

    /**
     * Mapeo de las columnas para que muestre los nombres en lugar de los IDs
     */
    public function map($visitaPrediosRiesgo): array
    {
        return [
            $visitaPrediosRiesgo->predio ? $visitaPrediosRiesgo->predio->nombre_predio : 'Sin predio',  // Nombre del predio
            $visitaPrediosRiesgo->enferm_baj_vigil,
            $visitaPrediosRiesgo->especie,
            $visitaPrediosRiesgo->num_anim_inspec,
            $visitaPrediosRiesgo->toma_muestras,
            $visitaPrediosRiesgo->toma_muestra_tipo,
            $visitaPrediosRiesgo->num_muestras,
        ];
    }

    /**
     * Definir las cabeceras del archivo Excel
     */
    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Enfermedades bajo vigilancia',
            'Especie',
            'Número de animales inspeccionados',
            'Toma de muestras',
            'Tipo de muestra',
            'Número de muestras',
        ];
    }

    /**
     * Aplicar estilos y espaciar las celdas
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo de cabeceras en negrita
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        // Aumentar el ancho de las columnas
        foreach (range('A', 'G') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    /**
     * Definir el título de la hoja
     */
    public function title(): string
    {
        return 'VISITA A PREDIOS DE RIESGO';
    }
}
