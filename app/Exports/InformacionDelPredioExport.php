<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\Areas;
use App\Models\InfoTierraAgua;
use App\Models\ManPastPotrCer;
use App\Models\ManGenGanado;
use App\Models\InformAspectMedAmbient;
use App\Models\InstalacionesEquipos;
use App\Models\GestionInformacion;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithTitle;

class InformacionDelPredioExport implements WithMultipleSheets
{
    protected $prediosFiltrados;

    public function __construct($prediosFiltrados = 'all')
    {
        $this->prediosFiltrados = $prediosFiltrados;
    }

    /**
     * Se definen las hojas del archivo Excel
     */
    public function sheets(): array
    {
        return [
            new AreasSheetExport($this->prediosFiltrados),
            new InfoTierraAguaSheetExport($this->prediosFiltrados),
            new ManPastPotrCerSheetExport($this->prediosFiltrados),
            new ManGenGanadoSheetExport($this->prediosFiltrados),
            new InformAspectMedAmbientSheetExport($this->prediosFiltrados),
            new InstalacionesEquiposSheetExport($this->prediosFiltrados),
            new GestionInformacionSheetExport($this->prediosFiltrados),
        ];
    }
}

// ==================== HOJA 1: ÁREAS ====================
class AreasSheetExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    protected $prediosFiltrados;

    public function __construct($prediosFiltrados = 'all')
    {
        $this->prediosFiltrados = $prediosFiltrados;
    }

    public function collection()
    {
        $query = Areas::with(['predio', 'TiposAreas']);

        if ($this->prediosFiltrados !== 'all' && !empty($this->prediosFiltrados)) {
            $query->whereIn('id_predio', $this->prediosFiltrados);
        }

        return $query->orderBy('id_predio')->get();
    }

    public function map($area): array
    {
        return [
            $area->TiposAreas ? $area->TiposAreas->nombre_area : 'Sin definir',
            $area->predio ? $area->predio->nombre_predio : 'Sin predio',
            $area->medidas,
            $area->tipo_medidas,
            $area->materiales_establecidos,
            $area->cant_total,
            $area->imagen,
            $area->created_at ? $area->created_at->format('Y-m-d H:i:s') : '',
            $area->updated_at ? $area->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }

    public function headings(): array
    {
        return [
            'Nombre del Área',
            'Nombre del Predio',
            'Medidas',
            'Tipo de Medidas',
            'Materiales Establecidos',
            'Cantidad Total',
            'Imagen',
            'Fecha de Creación',
            'Fecha de Actualización',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']]
        ]);

        foreach (range('A', 'I') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    public function title(): string
    {
        return 'INFORMACIÓN DE ÁREAS';
    }
}

// ==================== HOJA 2: INFO TIERRA Y AGUA ====================
class InfoTierraAguaSheetExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    protected $prediosFiltrados;

    public function __construct($prediosFiltrados = 'all')
    {
        $this->prediosFiltrados = $prediosFiltrados;
    }

    public function collection()
    {
        $query = InfoTierraAgua::with('Predios');

        if ($this->prediosFiltrados !== 'all' && !empty($this->prediosFiltrados)) {
            $query->whereIn('id_predio', $this->prediosFiltrados);
        }

        return $query->orderBy('id_predio')->get();
    }

    public function map($info): array
    {
        return [
            $info->Predios ? $info->Predios->nombre_predio : 'Sin predio',
            $info->suelos_predominantes,
            $info->drenaje,
            $info->manejo_cuencas_nac_agua,
            $info->cantidad_preservacion,
            $info->porcentaje_preservacion,
            $info->fuente_calidad_agua,
            $info->fuente_calidad_agua_uso_domestic,
            $info->disp_agua_durant_veran_anim,
            $info->disp_agua_durant_veran_anim_fuente,
            $info->disp_agua_durant_veran_riesg,
            $info->disp_agua_durant_veran_riesg_fuente,
            $info->created_at ? $info->created_at->format('Y-m-d H:i:s') : '',
            $info->updated_at ? $info->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }

    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Suelos Predominantes',
            'Drenaje',
            'Manejo de Cuencas y Nacimientos de Agua',
            'Cantidad de Preservación',
            'Porcentaje de Preservación',
            'Fuente de Calidad de Agua',
            'Fuente de Calidad de Agua para Uso Doméstico',
            'Disponibilidad de Agua para Animales en Verano',
            'Fuente de Agua para Animales en Verano',
            'Disponibilidad de Agua para Riego en Verano',
            'Fuente de Agua para Riego en Verano',
            'Fecha de Creación',
            'Fecha de Actualización',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:N1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '4472C4']]
        ]);

        foreach (range('A', 'N') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    public function title(): string
    {
        return 'INFORMACIÓN TIERRA Y AGUA';
    }
}

// ==================== HOJA 3: MANEJO DE PASTOS ====================
class ManPastPotrCerSheetExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    protected $prediosFiltrados;

    public function __construct($prediosFiltrados = 'all')
    {
        $this->prediosFiltrados = $prediosFiltrados;
    }

    public function collection()
    {
        $query = ManPastPotrCer::with('propietarios');

        if ($this->prediosFiltrados !== 'all' && !empty($this->prediosFiltrados)) {
            $query->whereIn('id_predio', $this->prediosFiltrados);
        }

        return $query->orderBy('id_predio')->get();
    }

    public function map($past): array
    {
        return [
            $past->propietarios ? $past->propietarios->nombre_predio : 'Sin predio',
            $past->area_dest_past,
            $past->r_fertilazion_potreros,
            $past->r_fertilazion_potreros_produc,
            $past->r_fertilazion_potreros_cuant_año,
            $past->presen_plag_enferm,
            $past->presen_plag_enferm_tipos,
            $past->r_control_plagas,
            $past->r_control_plagas_produc,
            $past->r_control_plagas_cuant_año,
            $past->r_control_maleza,
            $past->r_control_maleza_product,
            $past->r_control_maleza_cuant_año,
            $past->precencia_heladas,
            $past->precencia_heladas_intensidad,
            $past->precencia_heladas_epocas,
            $past->div_potreros,
            $past->div_potreros_como,
            $past->tipo_pastoreo,
            $past->rotacional_dias_ocupacion,
            $past->rotacional_dias_descanso,
            $past->cercas,
            $past->cercas_puas,
            $past->cercas_electricas,
            $past->la_produccion_forraje_suficiente_año,
            $past->porque,
            $past->created_at ? $past->created_at->format('Y-m-d H:i:s') : '',
            $past->updated_at ? $past->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }

    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Área Destinada a Pastos',
            'Realiza Fertilización de Potreros',
            'Producto de Fertilización',
            'Cantidad de Fertilización al Año',
            'Presencia de Plagas y Enfermedades',
            'Tipos de Plagas y Enfermedades',
            'Realiza Control de Plagas',
            'Producto para Control de Plagas',
            'Cantidad de Control de Plagas al Año',
            'Realiza Control de Maleza',
            'Producto para Control de Maleza',
            'Cantidad de Control de Maleza al Año',
            'Presencia de Heladas',
            'Intensidad de Heladas',
            'Épocas de Heladas',
            'División de Potreros',
            '¿Cómo están Divididos los Potreros?',
            'Tipo de Pastoreo',
            'Días de Ocupación (Rotacional)',
            'Días de Descanso (Rotacional)',
            'Cercas',
            'Cercas de Púas',
            'Cercas Eléctricas',
            'Producción de Forraje Suficiente en el Año',
            '¿Por qué?',
            'Fecha de Creación',
            'Fecha de Actualización',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:AB1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '70AD47']]
        ]);

        foreach (range('A', 'AB') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    public function title(): string
    {
        return 'MANEJO DE PASTOS Y POTREROS';
    }
}

// ==================== HOJA 4: MANEJO GENERAL DE GANADO ====================
class ManGenGanadoSheetExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    protected $prediosFiltrados;

    public function __construct($prediosFiltrados = 'all')
    {
        $this->prediosFiltrados = $prediosFiltrados;
    }

    public function collection()
    {
        $query = ManGenGanado::with(['predio', 'razas']);

        if ($this->prediosFiltrados !== 'all' && !empty($this->prediosFiltrados)) {
            $query->whereIn('id_predio', $this->prediosFiltrados);
        }

        return $query->orderBy('id_predio')->get();
    }

    public function map($ganado): array
    {
        return [
            // Columna 1: Nombre del Predio
            $ganado->predio ? $ganado->predio->nombre_predio : 'Sin predio',
            
            // Columna 2: Raza de Ganado
            $ganado->razas ? $ganado->razas->nombre_razas : 'Sin raza',
            
            // Columna 3: ident_animales (NO identif_anim)
            $ganado->ident_animales,
            
            // Columna 4: sistema_cria_ternero (NO sist_cria_ternero)
            $ganado->sistema_cria_ternero,
            
            // Columna 5: aliment_ternero (NO alimentacion_ternero)
            $ganado->aliment_ternero,
            
            // Columna 6: sistem_levant_animal (NO sist_levante_animal)
            $ganado->sistem_levant_animal,
            
            // Columna 7: manej_hembras_prox (NO man_hembr_prox_parir)
            $ganado->manej_hembras_prox,
            
            // Columna 8: manej_vacas_secas
            $ganado->manej_vacas_secas,
            
            // Columna 9: tipo_ordeño
            $ganado->tipo_ordeño,
            
            // Columna 10: sistem_servic_reproduct (NO sist_servic_reproduct)
            $ganado->sistem_servic_reproduct,
            
            // Columna 11: form_program_servicios (NO forma_progr_servic)
            $ganado->form_program_servicios,
            
            // Columna 12: pesaje_animal (NO pesaje_animales)
            $ganado->pesaje_animal,
            
            // Columna 13: cuantos_animal_pesa (NO num_animl_pesados)
            $ganado->cuantos_animal_pesa,
            
            // Columna 14: control_parasito_extern (NO control_paras_externos)
            $ganado->control_parasito_extern,
            
            // Columna 15: control_parasito_extern_produc (NO control_paras_externos_product)
            $ganado->control_parasito_extern_produc,
            
            // Columna 16: control_parasito_extern_frecuenc (NO control_paras_externos_frecuen)
            $ganado->control_parasito_extern_frecuenc,
            
            // Columna 17: control_parasito_intern (NO control_paras_internos)
            $ganado->control_parasito_intern,
            
            // Columna 18: control_parasito_intern_produc (NO control_paras_internos_product)
            $ganado->control_parasito_intern_produc,
            
            // Columna 19: control_parasito_intern_frecuenc (NO control_paras_internos_frecuen)
            $ganado->control_parasito_intern_frecuenc,
            
            // Columna 20: sumin_sal (NO suministro_sal)
            $ganado->sumin_sal,
            
            // Columna 21: a_sal_add_premezcla (NO sal_adicion_premix)
            $ganado->a_sal_add_premezcla,
            
            // Columna 22: a_sal_add_premezcla_especifique (NO sal_adicion_premix_especif)
            $ganado->a_sal_add_premezcla_especifique,
            
            // Columna 23: como_manej_ganad_veran (NO manejo_gand_verano)
            $ganado->como_manej_ganad_veran,
            
            // Columna 24: como_manej_ganad_invier (NO manejo_gand_invierno)
            $ganado->como_manej_ganad_invier,
            
            // Columna 25: r_pesaje_leche_hembr_lactantes (NO pesaj_leche_hembr_lactante)
            $ganado->r_pesaje_leche_hembr_lactantes,
            
            // Columna 26: r_pesaje_leche_hembr_periodicidad (NO period_pesaj_leche)
            $ganado->r_pesaje_leche_hembr_periodicidad,
            
            // Columna 27: suplement_ganad_epoc_criti (NO suplement_epoc_critic)
            $ganado->suplement_ganad_epoc_criti,
            
            // Columna 28: suplement_ganad_epoc_criti_con_que (NO suplement_epoc_critic_con_que)
            $ganado->suplement_ganad_epoc_criti_con_que,
            
            // Columna 29: suplement_ganad_epoc_criti_que_lotes (NO suplement_epoc_criti_que_lotes)
            $ganado->suplement_ganad_epoc_criti_que_lotes,
            
            // Columna 30: Fecha de Creación
            $ganado->created_at ? $ganado->created_at->format('Y-m-d H:i:s') : '',
            
            // Columna 31: Fecha de Actualización
            $ganado->updated_at ? $ganado->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }

    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Raza de Ganado',
            'Identificación de Animales',
            'Sistema de Cría del Ternero',
            'Alimentación del Ternero',
            'Sistema de Levante del Animal',
            'Manejo de Hembras Próximas a Parir',
            'Manejo de Vacas Secas',
            'Tipo de Ordeño',
            'Sistema de Servicio Reproductivo',
            'Forma de Programar Servicios',
            'Pesaje de Animales',
            'Número de Animales Pesados',
            'Control de Parásitos Externos',
            'Producto para Control de Parásitos Externos',
            'Frecuencia de Control de Parásitos Externos',
            'Control de Parásitos Internos',
            'Producto para Control de Parásitos Internos',
            'Frecuencia de Control de Parásitos Internos',
            'Suministro de Sal',
            'Adición de Premix a la Sal',
            'Especificar Premix en la Sal',
            'Manejo del Ganado en Verano',
            'Manejo del Ganado en Invierno',
            'Pesaje de Leche en Hembras Lactantes',
            'Periodicidad del Pesaje de Leche',
            'Suplementación en Épocas Críticas',
            'Con qué se Suplementa en Épocas Críticas',
            'Lotes que Reciben Suplementación',
            'Fecha de Creación',
            'Fecha de Actualización',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:AE1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFC000']]
        ]);

        foreach (range('A', 'AE') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    public function title(): string
    {
        return 'MANEJO DE GANADO';
    }
}

// ==================== HOJA 5: MEDIO AMBIENTE ====================
class InformAspectMedAmbientSheetExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    protected $prediosFiltrados;

    public function __construct($prediosFiltrados = 'all')
    {
        $this->prediosFiltrados = $prediosFiltrados;
    }

    public function collection()
    {
        $query = InformAspectMedAmbient::with(['Predios']);

        if ($this->prediosFiltrados !== 'all' && !empty($this->prediosFiltrados)) {
            $query->whereIn('id_predio', $this->prediosFiltrados);
        }

        return $query->orderBy('id_predio')->get();
    }

    public function map($ambient): array
    {
        return [
            $ambient->Predios ? $ambient->Predios->nombre_predio : 'Sin predio',
            $ambient->dispos_aguas_servid,
            $ambient->dispos_excrement_bovinos,
            $ambient->manejo_basuras,
            $ambient->manejo_empaq_produc_quimic,
            $ambient->created_at ? $ambient->created_at->format('Y-m-d H:i:s') : '',
            $ambient->updated_at ? $ambient->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }

    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Disposición de Aguas Servidas',
            'Disposición de Excrementos Bovinos',
            'Manejo de Basuras',
            'Manejo de Empaques de Productos Químicos',
            'Fecha de Creación',
            'Fecha de Actualización',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '70AD47']]
        ]);

        foreach (range('A', 'G') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    public function title(): string
    {
        return 'INFORMACIÓN MEDIO AMBIENTALES';
    }
}

// ==================== HOJA 6: INSTALACIONES Y EQUIPOS ====================
class InstalacionesEquiposSheetExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    protected $prediosFiltrados;

    public function __construct($prediosFiltrados = 'all')
    {
        $this->prediosFiltrados = $prediosFiltrados;
    }

    public function collection()
    {
        $query = InstalacionesEquipos::with(['Predios', 'tipos_equipos']);

        if ($this->prediosFiltrados !== 'all' && !empty($this->prediosFiltrados)) {
            $query->whereIn('id_predio', $this->prediosFiltrados);
        }

        return $query->orderBy('id_predio')->get();
    }

    public function map($equipo): array
    {
        return [
            $equipo->Predios ? $equipo->Predios->nombre_predio : 'Sin predio',
            $equipo->tipos_equipos ? $equipo->tipos_equipos->nombre_tipo : 'Sin tipo de equipo',
            $equipo->si,
            $equipo->no,
            $equipo->especificar,
            $equipo->created_at ? $equipo->created_at->format('Y-m-d H:i:s') : '',
            $equipo->updated_at ? $equipo->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }

    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Tipo de Equipo',
            'Si',
            'No',
            'Especificar',
            'Fecha de Creación',
            'Fecha de Actualización',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'ED7D31']]
        ]);

        foreach (range('A', 'G') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    public function title(): string
    {
        return 'INSTALACIONES Y EQUIPOS';
    }
}

// ==================== HOJA 7: GESTIÓN DE INFORMACIÓN ====================
class GestionInformacionSheetExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    protected $prediosFiltrados;

    public function __construct($prediosFiltrados = 'all')
    {
        $this->prediosFiltrados = $prediosFiltrados;
    }

    public function collection()
    {
        $query = GestionInformacion::with(['Predios']);

        if ($this->prediosFiltrados !== 'all' && !empty($this->prediosFiltrados)) {
            $query->whereIn('id_predio', $this->prediosFiltrados);
        }

        return $query->orderBy('id_predio')->get();
    }

    public function map($gestion): array
    {
        return [
            $gestion->Predios ? $gestion->Predios->nombre_predio : 'Sin predio',
            $gestion->donde_regis_info_finca,
            $gestion->los_registros_son,
            $gestion->calcula_indicadores,
            $gestion->calcula_indicadores_de,
            $gestion->calcula_indicadores_de_para,
            $gestion->la_informacion_es,
            $gestion->utiliza_software_monitore,
            $gestion->utiliza_software_monitore_cual,
            $gestion->created_at ? $gestion->created_at->format('Y-m-d H:i:s') : '',
            $gestion->updated_at ? $gestion->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }

    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Dónde Registra la Información de la Finca',
            'Los Registros Son',
            'Calcula Indicadores',
            'Calcula Indicadores de',
            'Calcula Indicadores de Para',
            'La Información es',
            'Utiliza Software para Monitoreo',
            'Utiliza Software para Monitoreo (Cuál)',
            'Fecha de Creación',
            'Fecha de Actualización',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:K1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '5B9BD5']]
        ]);

        foreach (range('A', 'K') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    public function title(): string
    {
        return 'GESTIÓN DE INFORMACIÓN';
    }
}