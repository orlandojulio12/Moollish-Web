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
    /**
     * Se definen las hojas del archivo Excel
     */
    public function sheets(): array
    {
        return [
            new AreasSheetExport(),  // Hoja 1
            new InfoTierraAguaSheetExport(),  // Hoja 2
            new ManPastPotrCerSheetExport(),  // Hoja 3
            new ManGenGanadoSheetExport(),  // Hoja 4
            new InformAspectMedAmbientSheetExport(),  // Hoja 5
            new InstalacionesEquiposSheetExport(),  // Hoja 6
            new GestionInformacionSheetExport(),  // Hoja 7: Gestión de Información
        ];
    }
}

// Exportar datos de Áreas en la primera hoja y asignar nombre a la hoja
class AreasSheetExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    public function collection()
    {
        return Areas::with(['predio', 'TiposAreas'])->get();
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
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        foreach (range('A', 'G') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    public function title(): string
    {
        return 'INFORMACIÓN DE ÁREAS';
    }
}

// Exportar datos de info_tierra_agua en la segunda hoja y asignar nombre a la hoja
class InfoTierraAguaSheetExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    public function collection()
    {
        return InfoTierraAgua::with('Predios')->get();
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
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        foreach (range('A', 'L') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    public function title(): string
    {
        return 'INFORMACIÓN TIERRA Y AGUA';
    }
}

// Exportar datos de man_past_potr_cerc en la tercera hoja y asignar nombre a la hoja
class ManPastPotrCerSheetExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    public function collection()
    {
        return ManPastPotrCer::with('propietarios')->get();
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
        ];
    }

    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Área Destinada a Pastos',
            'Fertilización de Potreros',
            'Fertilización de Potreros Producción',
            'Fertilización de Potreros Cuantas Veces al Año',
            'Presencia de Plagas o Enfermedades',
            'Tipos de Plagas o Enfermedades',
            'Control de Plagas',
            'Control de Plagas Producción',
            'Control de Plagas Cuantas Veces al Año',
            'Control de Maleza',
            'Control de Maleza Producción',
            'Control de Maleza Cuantas Veces al Año',
            'Presencia de Heladas',
            'Intensidad de Heladas',
            'Épocas de Heladas',
            'División de Potreros',
            'Cómo se Dividen los Potreros',
            'Tipo de Pastoreo',
            'Días de Ocupación del Pastoreo Rotacional',
            'Días de Descanso del Pastoreo Rotacional',
            'Cercas',
            'Cercas de Púas',
            'Cercas Eléctricas',
            'Producción de Forraje Suficiente Todo el Año',
            'Razón de Insuficiencia de Forraje',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:Z1')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        foreach (range('A', 'Z') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    public function title(): string
    {
        return 'MANEJO DE PASTOS';
    }
}

class ManGenGanadoSheetExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    /**
     * Retornar los datos de la tabla 'man_gen_ganado' con relaciones
     */
    public function collection()
    {
        return ManGenGanado::with(['propietarios', 'razas'])->get();
    }

    /**
     * Mapeo de las columnas para que muestre los nombres en lugar de los IDs
     */
    public function map($ganado): array
    {
        return [
            $ganado->propietarios ? $ganado->propietarios->nombre_predio : 'Sin predio',  // Nombre del predio
            $ganado->razas ? $ganado->razas->nombre_razas : 'Sin raza',  // Nombre de la raza de ganado
            $ganado->ident_animales,
            $ganado->sistema_cria_ternero,
            $ganado->aliment_ternero,
            $ganado->sistem_levant_animal,
            $ganado->manej_hembras_prox,
            $ganado->manej_vacas_secas,
            $ganado->tipo_ordeño,
            $ganado->sistem_servic_reproduct,
            $ganado->form_program_servicios,
            $ganado->pesaje_animal,
            $ganado->cuantos_animal_pesa,
            $ganado->control_parasito_extern,
            $ganado->control_parasito_extern_produc,
            $ganado->control_parasito_extern_frecuenc,
            $ganado->control_parasito_intern,
            $ganado->control_parasito_intern_produc,
            $ganado->control_parasito_intern_frecuenc,
            $ganado->sumin_sal,
            $ganado->a_sal_add_premezcla,
            $ganado->a_sal_add_premezcla_especifique,
            $ganado->como_manej_ganad_veran,
            $ganado->como_manej_ganad_invier,
            $ganado->r_pesaje_leche_hembr_lactantes,
            $ganado->r_pesaje_leche_hembr_periodicidad,
            $ganado->suplement_ganad_epoc_criti,
            $ganado->suplement_ganad_epoc_criti_con_que,
            $ganado->suplement_ganad_epoc_criti_que_lotes,
        ];
    }

    /**
     * Definir las cabeceras del archivo Excel
     */
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
        ];
    }

    /**
     * Aplicar estilos y espaciar las celdas
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo de cabeceras en negrita
        $sheet->getStyle('A1:AC1')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        // Aumentar el ancho de las columnas
        foreach (range('A', 'AC') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    /**
     * Asignar el título de la hoja
     */
    public function title(): string
    {
        return 'MANEJO DE GANADO';
    }
}

class InformAspectMedAmbientSheetExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    /**
     * Retornar los datos de la tabla 'inform_aspect_med_ambient' con relaciones
     */
    public function collection()
    {
        return InformAspectMedAmbient::with(['Predios'])->get();
    }

    /**
     * Mapeo de las columnas para que muestre los nombres en lugar de los IDs
     */
    public function map($ambient): array
    {
        return [
            $ambient->Predios ? $ambient->Predios->nombre_predio : 'Sin predio',  // Nombre del predio
            $ambient->dispos_aguas_servid,
            $ambient->dispos_excrement_bovinos,
            $ambient->manejo_basuras,
            $ambient->manejo_empaq_produc_quimic,
        ];
    }

    /**
     * Definir las cabeceras del archivo Excel
     */
    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Disposición de Aguas Servidas',
            'Disposición de Excrementos Bovinos',
            'Manejo de Basuras',
            'Manejo de Empaques de Productos Químicos',
        ];
    }

    /**
     * Aplicar estilos y espaciar las celdas
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo de cabeceras en negrita
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        // Aumentar el ancho de las columnas
        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    /**
     * Asignar el título de la hoja
     */
    public function title(): string
    {
        return 'INFORMACIÓN MEDIO AMBIENTALES';
    }
}

class InstalacionesEquiposSheetExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    /**
     * Retornar los datos de la tabla 'instalaciones_equipos' con relaciones
     */
    public function collection()
    {
        return InstalacionesEquipos::with(['Predios', 'tipos_equipos'])->get();
    }

    /**
     * Mapeo de las columnas para que muestre los nombres en lugar de los IDs
     */
    public function map($equipo): array
    {
        return [
            $equipo->Predios ? $equipo->Predios->nombre_predio : 'Sin predio',  // Nombre del predio
            $equipo->tipos_equipos ? $equipo->tipos_equipos->nombre_tipo : 'Sin tipo de equipo',  // Nombre del tipo de equipo
            $equipo->si,
            $equipo->no,
            $equipo->especificar,
        ];
    }

    /**
     * Definir las cabeceras del archivo Excel
     */
    public function headings(): array
    {
        return [
            'Nombre del Predio',
            'Tipo de Equipo',
            'Si',
            'No',
            'Especificar',
        ];
    }

    /**
     * Aplicar estilos y espaciar las celdas
     */
    public function styles(Worksheet $sheet)
    {
        // Estilo de cabeceras en negrita
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        // Aumentar el ancho de las columnas
        foreach (range('A', 'E') as $columnID) {
            $sheet->getColumnDimension($columnID)->setWidth(25);
        }
    }

    /**
     * Asignar el título de la hoja
     */
    public function title(): string
    {
        return 'INSTALACIONES Y EQUIPOS';
    }
}

class GestionInformacionSheetExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    /**
     * Retornar los datos de la tabla 'gestion_informacion' con relaciones
     */
    public function collection()
    {
        return GestionInformacion::with(['Predios'])->get();
    }

    /**
     * Mapeo de las columnas para que muestre los nombres en lugar de los IDs
     */
    public function map($gestion): array
    {
        return [
            $gestion->Predios ? $gestion->Predios->nombre_predio : 'Sin predio',  // Nombre del predio
            $gestion->donde_regis_info_finca,
            $gestion->los_registros_son,
            $gestion->calcula_indicadores,
            $gestion->calcula_indicadores_de,
            $gestion->calcula_indicadores_de_para,
            $gestion->la_informacion_es,
            $gestion->utiliza_software_monitore,
            $gestion->utiliza_software_monitore_cual,
        ];
    }

    /**
     * Definir las cabeceras del archivo Excel
     */
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
     * Asignar el título de la hoja
     */
    public function title(): string
    {
        return 'GESTIÓN DE INFORMACIÓN';
    }
}
