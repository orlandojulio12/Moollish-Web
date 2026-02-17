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
    protected $prediosFiltrados;

    public function __construct($prediosFiltrados = 'all')
    {
        $this->prediosFiltrados = $prediosFiltrados;
    }

    /**
     * Retornar las hojas de cálculo
     */
    public function sheets(): array
    {
        return [
            new TipoExplotacionExport($this->prediosFiltrados),
            new InforEpidemiologicaExport($this->prediosFiltrados),
            new CaracterizacionRiesgoExport($this->prediosFiltrados),
            new VisitaPrediosRiesgoExport($this->prediosFiltrados),
        ];
    }
}

// ==================== HOJA 1: TIPO EXPLOTACIÓN ====================
class TipoExplotacionExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    protected $prediosFiltrados;

    public function __construct($prediosFiltrados = 'all')
    {
        $this->prediosFiltrados = $prediosFiltrados;
    }

    public function collection()
    {
        $query = Tipo_explotacion::with('predio');

        if ($this->prediosFiltrados !== 'all' && !empty($this->prediosFiltrados)) {
            $query->whereIn('id_predio', $this->prediosFiltrados);
        }

        return $query->orderBy('id_predio')->get();
    }

    public function map($tipoExplotacion): array
    {
        return [
            $tipoExplotacion->predio ? $tipoExplotacion->predio->nombre_predio : 'Sin predio',
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
            $tipoExplotacion->created_at ? $tipoExplotacion->created_at->format('Y-m-d H:i:s') : '',
            $tipoExplotacion->updated_at ? $tipoExplotacion->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }

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
            'Fecha de Creación',
            'Fecha de Actualización',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:T1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '548235']]
        ]);

        foreach (range('A', 'T') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    public function title(): string
    {
        return 'TIPO EXPLOTACIÓN';
    }
}

// ==================== HOJA 2: INFORMACIÓN EPIDEMIOLÓGICA ====================
class InforEpidemiologicaExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    protected $prediosFiltrados;

    public function __construct($prediosFiltrados = 'all')
    {
        $this->prediosFiltrados = $prediosFiltrados;
    }

    public function collection()
    {
        $query = InforEpidemiologica::with('predio');

        if ($this->prediosFiltrados !== 'all' && !empty($this->prediosFiltrados)) {
            $query->whereIn('id_predio', $this->prediosFiltrados);
        }

        return $query->orderBy('id_predio')->get();
    }

    public function map($inforEpidemiologica): array
    {
        return [
            $inforEpidemiologica->predio ? $inforEpidemiologica->predio->nombre_predio : 'Sin predio',
            $inforEpidemiologica->anim_enferm_control,
            $inforEpidemiologica->anim_enferm_control_cant,
            $inforEpidemiologica->cuadr_clinc_sospec,
            $inforEpidemiologica->especies_afectadas,
            $inforEpidemiologica->toma_muestra,
            $inforEpidemiologica->toma_muestra_tipos,
            $inforEpidemiologica->toma_muestra_numeros,
            $inforEpidemiologica->created_at ? $inforEpidemiologica->created_at->format('Y-m-d H:i:s') : '',
            $inforEpidemiologica->updated_at ? $inforEpidemiologica->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }

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
            'Fecha de Creación',
            'Fecha de Actualización',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'C00000']]
        ]);

        foreach (range('A', 'J') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    public function title(): string
    {
        return 'INFORMACIÓN EPIDEMIOLÓGICA';
    }
}

// ==================== HOJA 3: CARACTERIZACIÓN DE RIESGO ====================
class CaracterizacionRiesgoExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    protected $prediosFiltrados;

    public function __construct($prediosFiltrados = 'all')
    {
        $this->prediosFiltrados = $prediosFiltrados;
    }

    public function collection()
    {
        $query = CarazterizacionRiesgo::with('predio');

        if ($this->prediosFiltrados !== 'all' && !empty($this->prediosFiltrados)) {
            $query->whereIn('id_predio', $this->prediosFiltrados);
        }

        return $query->orderBy('id_predio')->get();
    }

    public function map($caracterizacionRiesgo): array
    {
        return [
            $caracterizacionRiesgo->predio ? $caracterizacionRiesgo->predio->nombre_predio : 'Sin predio',
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
            $caracterizacionRiesgo->created_at ? $caracterizacionRiesgo->created_at->format('Y-m-d H:i:s') : '',
            $caracterizacionRiesgo->updated_at ? $caracterizacionRiesgo->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }

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
            'Fecha de Creación',
            'Fecha de Actualización',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:T1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FF6600']]
        ]);

        foreach (range('A', 'T') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    public function title(): string
    {
        return 'CARACTERIZACIÓN DE RIESGO';
    }
}

// ==================== HOJA 4: VISITA A PREDIOS DE RIESGO ====================
class VisitaPrediosRiesgoExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    protected $prediosFiltrados;

    public function __construct($prediosFiltrados = 'all')
    {
        $this->prediosFiltrados = $prediosFiltrados;
    }

    public function collection()
    {
        $query = VisitaPrediosRiesgo::with('predio');

        if ($this->prediosFiltrados !== 'all' && !empty($this->prediosFiltrados)) {
            $query->whereIn('id_predio', $this->prediosFiltrados);
        }

        return $query->orderBy('id_predio')->get();
    }

    public function map($visitaPrediosRiesgo): array
    {
        return [
            $visitaPrediosRiesgo->predio ? $visitaPrediosRiesgo->predio->nombre_predio : 'Sin predio',
            $visitaPrediosRiesgo->fecha_visita,
            $visitaPrediosRiesgo->observaciones,
            $visitaPrediosRiesgo->responsable,
            $visitaPrediosRiesgo->created_at ? $visitaPrediosRiesgo->created_at->format('Y-m-d H:i:s') : '',
            $visitaPrediosRiesgo->updated_at ? $visitaPrediosRiesgo->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }

    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Fecha de Visita',
            'Observaciones',
            'Responsable',
            'Fecha de Creación',
            'Fecha de Actualización',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '7030A0']]
        ]);

        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    public function title(): string
    {
        return 'VISITA PREDIOS RIESGO';
    }
}