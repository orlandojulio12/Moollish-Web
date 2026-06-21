<?php

namespace App\Imports;

use App\Models\Animal;
use App\Models\PartoAnimal;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class AnimalesImport implements ToModel, WithStartRow
{
    protected $predio_id;
    protected $errores = [];
    protected $fila = 12;
    protected $animalesCreados = [];

    const ESTADO_PRODUCTIVO_MAP = [
        'VP' => 1,  // Vaca Parida
        'VS' => 2,  // Vaca Seca
        'NV' => 3,  // Novilla de vientre
        'HL' => 4,  // Hembra de levante
        'CH' => 7,  // Cria Hembra
        'TR' => 12, // Toro Reproductor
        'MC' => 13, // Macho de ceba
        'ML' => 14, // Macho de levante
        'CM' => 15, // Cria Macho
    ];

    public function __construct($predio_id)
    {
        $this->predio_id = $predio_id;
    }

    public function startRow(): int
    {
        return 13;
    }

    public function model(array $row)
    {
        $this->fila++;

        try {
            if (empty($row[0]) || trim($row[0]) === '') {
                return null;
            }

            $sexoRaw = strtoupper(trim($row[2] ?? ''));
            $sexo = $sexoRaw === 'M' ? 'macho' : ($sexoRaw === 'H' ? 'hembra' : '');

            if (empty($sexo)) {
                $this->errores[] = "Fila {$this->fila}: Sexo invalido '{$row[2]}'";
                return null;
            }

            $fecha_nacimiento = null;
            if (!empty($row[3]) && is_numeric($row[3])) {
                try {
                    $fecha_nacimiento = Date::excelToDateTimeObject($row[3])->format('Y-m-d');
                } catch (\Exception $e) {
                    $this->errores[] = "Fila {$this->fila}: Fecha invalida";
                }
            }

            $estadoProductivoId = null;
            $estadoRaw = strtoupper(trim($row[5] ?? ''));

            if (!empty($estadoRaw) && isset(self::ESTADO_PRODUCTIVO_MAP[$estadoRaw])) {
                $estadoProductivoId = self::ESTADO_PRODUCTIVO_MAP[$estadoRaw];
            } elseif (!empty($estadoRaw)) {
                $this->errores[] = "Fila {$this->fila}: Estado productivo '{$estadoRaw}' no reconocido";
            }

            $id_padre = null;
            if (!empty($row[7])) {
                $padre = Animal::where('codigo', trim($row[7]))
                    ->where('id_predio', $this->predio_id)
                    ->where('sexo', 'macho')
                    ->first();
                if ($padre) {
                    $id_padre = $padre->id_animal;
                }
            }

            // Buscar madre por codigo (columna index 6 = "# Madre")
            $id_madre = null;
            $codigo_madre_raw = !empty($row[6]) ? trim($row[6]) : null;
            if ($codigo_madre_raw) {
                $madre = Animal::where('codigo', $codigo_madre_raw)
                    ->where('id_predio', $this->predio_id)
                    ->where('sexo', 'hembra')
                    ->first();
                if ($madre) {
                    $id_madre = $madre->id_animal;
                }
            }

            $codigo = trim($row[0]);
            $existe = Animal::where('codigo', $codigo)
                ->where('id_predio', $this->predio_id)
                ->exists();

            if ($existe) {
                $this->errores[] = "Fila {$this->fila}: Codigo '{$codigo}' ya existe";
                return null;
            }

            $animal = new Animal([
                'id_predio'            => $this->predio_id,
                'codigo'               => $codigo,
                'nombre'               => trim($row[1] ?? ''),
                'sexo'                 => $sexo,
                'fecha_nacimiento'     => $fecha_nacimiento,
                'raza'                 => trim($row[4] ?? ''),
                'estado_productivo_id' => $estadoProductivoId,
                'padre'                => $id_padre,
                'madre'                => $id_madre,
                'hierro'               => !empty($row[8]) ? trim($row[8]) : null,
                'color'                => !empty($row[9]) ? trim($row[9]) : null,
                'estado_vida'          => 1,
                'fecha_ingreso_hato'   => now(),
            ]);

            $this->animalesCreados[] = [
                'codigo'        => $codigo,
                'id_madre'      => $id_madre,
                'codigo_madre'  => $codigo_madre_raw,
            ];

            return $animal;

        } catch (\Exception $e) {
            $this->errores[] = "Fila {$this->fila}: Error - {$e->getMessage()}";
            return null;
        }
    }

    public function registrarPartosAutomaticos()
    {
        foreach ($this->animalesCreados as $data) {
            $codigo       = $data['codigo'];
            $id_madre     = $data['id_madre'];
            $codigoMadre  = $data['codigo_madre'] ?? null;
            try {
                // BUG 2: si la madre no se encontró durante model() (aún no persistida),
                // intentar resolverla ahora que todo el Excel ya fue guardado
                if (!$id_madre && !empty($codigoMadre)) {
                    $madreReintento = Animal::where('codigo', $codigoMadre)
                        ->where('id_predio', $this->predio_id)
                        ->where('sexo', 'hembra')
                        ->first();
                    if ($madreReintento) {
                        $id_madre = $madreReintento->id_animal;
                    }
                }

                if (!$id_madre) {
                    continue;
                }

                // Recargar cría desde BD (ToModel asigna id_animal después del return)
                $criaFresh = Animal::where('codigo', $codigo)
                    ->where('id_predio', $this->predio_id)
                    ->first();

                // BUG 3: log para debug cuando no se puede crear el parto
                if (!$criaFresh || !$criaFresh->fecha_nacimiento) {
                    $this->errores[] = "Parto no creado para '{$codigo}': "
                        . ($criaFresh ? 'sin fecha_nacimiento' : 'animal no encontrado en BD');
                    continue;
                }

                // BUG 1: Animal usa primaryKey = 'id_animal', usar where en lugar de find()
                $madre = Animal::where('id_animal', $id_madre)->first();
                if (!$madre) {
                    $this->errores[] = "Parto no creado para '{$codigo}': madre id={$id_madre} no encontrada";
                    continue;
                }

                // Evitar duplicados si el import se ejecuta más de una vez
                $yaExiste = PartoAnimal::where('id_animal', $madre->id_animal)
                    ->where('id_cria', $criaFresh->id_animal)
                    ->exists();

                if ($yaExiste) {
                    continue;
                }

                DB::transaction(function () use ($madre, $criaFresh) {
                    $parto = PartoAnimal::create([
                        'tipo_parto'    => 'Parto',
                        'id_animal'     => $madre->id_animal,
                        'id_cria'       => $criaFresh->id_animal,
                        'fecha_parto'   => $criaFresh->fecha_nacimiento,
                        'observaciones' => 'Parto automatico desde importacion',
                    ]);

                    $parto->criasViaPivot()->attach($criaFresh->id_animal);
                });

            } catch (\Exception $e) {
                $this->errores[] = "Error parto cria '{$codigo}': {$e->getMessage()}";
            }
        }
    }

    public function getErrores()
    {
        return $this->errores;
    }
}
