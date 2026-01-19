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
                $this->errores[] = "Fila {$this->fila}: Sexo inválido '{$row[2]}'";
                return null;
            }

            $fecha_nacimiento = null;
            if (!empty($row[3]) && is_numeric($row[3])) {
                try {
                    $fecha_nacimiento = Date::excelToDateTimeObject($row[3])->format('Y-m-d');
                } catch (\Exception $e) {
                    $this->errores[] = "Fila {$this->fila}: Fecha inválida";
                }
            }

            $estadoProductivoId = null;
            $estadoRaw = strtoupper(trim($row[5] ?? ''));
            
            if (!empty($estadoRaw) && isset(self::ESTADO_PRODUCTIVO_MAP[$estadoRaw])) {
                $estadoProductivoId = self::ESTADO_PRODUCTIVO_MAP[$estadoRaw];
            } else if (!empty($estadoRaw)) {
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

            $id_madre = null;
            if (!empty($row[6])) {
                $madre = Animal::where('codigo', trim($row[6]))
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
                $this->errores[] = "Fila {$this->fila}: Código '{$codigo}' ya existe";
                return null;
            }

            $animal = new Animal([
                'id_predio' => $this->predio_id,
                'codigo' => $codigo,
                'nombre' => trim($row[1] ?? ''),
                'sexo' => $sexo,
                'fecha_nacimiento' => $fecha_nacimiento,
                'raza' => trim($row[4] ?? ''),
                'estado_productivo_id' => $estadoProductivoId,
                'padre' => $id_padre,
                'madre' => $id_madre,
                'hierro' => !empty($row[8]) ? trim($row[8]) : null,
                'color' => !empty($row[9]) ? trim($row[9]) : null, // NUEVO CAMPO
                'created_by' => Auth::id(),
                'estado_vida' => 1,
                'fecha_ingreso_hato' => now(),
            ]);

            // Guardar para procesamiento de partos
            $this->animalesCreados[] = [
                'animal' => $animal,
                'madre' => $id_madre,
                'estado_productivo_id' => $estadoProductivoId,
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
            try {
                $cria = $data['animal'];
                $id_madre = $data['id_madre'];
                $estadoProductivoId = $data['estado_productivo_id'];
                
                // Validar condiciones: tiene madre, es cría (CH=7 o CM=15)
                if (!$id_madre || !in_array($estadoProductivoId, [7, 15])) {
                    continue;
                }
                
                if (!$cria->fecha_nacimiento) {
                    continue;
                }
                
                // Verificar madre es Vaca Parida (VP=1)
                $madre = Animal::find($id_madre);
                if (!$madre || $madre->estado_productivo_id != 1) {
                    continue;
                }
                
                // Crear parto
                DB::transaction(function() use ($madre, $cria) {
                    $parto = PartoAnimal::create([
                        'tipo_parto' => 'Parto',
                        'id_animal' => $madre->id_animal,
                        'id_cria' => $cria->id_animal,
                        'fecha_parto' => $cria->fecha_nacimiento,
                        'observaciones' => 'Parto automático desde importación',
                    ]);
                    
                    $parto->criasViaPivot()->attach($cria->id_animal);
                });
                
            } catch (\Exception $e) {
                $this->errores[] = "Error parto cría {$cria->codigo}: {$e->getMessage()}";
            }
        }
    }

    public function getErrores()
    {
        return $this->errores;
    }
}