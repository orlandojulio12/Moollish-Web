<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Collection;
use App\Models\Animal;
use App\Models\PartoAnimal;
use DB;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class PartosImport implements ToCollection, WithStartRow, SkipsEmptyRows
{
    protected $predio_id;
    protected $errores = [];
    protected $duplicados = [];
    protected $exitosos = 0;

    public function __construct($predio_id)
    {
        $this->predio_id = $predio_id;
    }

    public function startRow(): int
    {
        return 17; // Datos empiezan en fila 17 (después de ejemplos)
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $fila = $index + 17; // +17 porque empezamos en fila 17

            try {
                // 1. VALIDAR CÓDIGO MADRE (columna 0)
                $codigoMadreRaw = trim($row[0] ?? '');
                if (empty($codigoMadreRaw)) {
                    continue; // Saltar filas vacías
                }

                // Extraer solo el código (antes del guion si existe)
                $codigoMadre = explode(' - ', $codigoMadreRaw)[0];

                $vaca = Animal::where('codigo', $codigoMadre)
                    ->where('id_predio', $this->predio_id)
                    ->where('sexo', 'hembra')
                    ->first();

                if (!$vaca) {
                    $this->errores[] = "Fila {$fila}: Código Madre '{$codigoMadre}' NO existe en el predio";
                    continue;
                }

                // 2. VALIDAR FECHA DE PARTO (columna 4)
                $fechaParto = null;
                if (!empty($row[4])) {
                    if (is_numeric($row[4])) {
                        try {
                            $fechaParto = Date::excelToDateTimeObject($row[4])->format('Y-m-d');
                        } catch (\Exception $e) {
                            $this->errores[] = "Fila {$fila}: Fecha de parto invalida";
                            continue;
                        }
                    } else {
                        $this->errores[] = "Fila {$fila}: Formato de fecha invalido";
                        continue;
                    }
                } else {
                    $this->errores[] = "Fila {$fila}: Fecha de parto vacia";
                    continue;
                }

                // 3. VALIDACIÓN DE DUPLICADO EXACTO: misma vaca + misma fecha + misma cría
                $codigoCriaTemporal = trim($row[1] ?? '');
                if (!empty($codigoCriaTemporal)) {
                    $partoExistente = PartoAnimal::where('id_animal', $vaca->id_animal)
                        ->whereDate('fecha_parto', $fechaParto)
                        ->whereHas('crias', function ($query) use ($codigoCriaTemporal) {
                            $query->where('codigo', $codigoCriaTemporal);
                        })
                        ->first();

                    if ($partoExistente) {
                        $this->duplicados[] = "Fila {$fila}: Parto DUPLICADO - Vaca '{$codigoMadre}' ya tiene registrada la cria '{$codigoCriaTemporal}' en {$fechaParto}";
                        continue;
                    }
                }

                // 4. DETERMINAR TIPO DE EVENTO (columna 10)
                $tipoEvento = strtoupper(trim($row[10] ?? ''));
                $esAbortoOMuerte = in_array($tipoEvento, ['ABORTO', 'MUERTE FETAL']);

                // 5. VALIDAR CÓDIGO CRÍA (columna 1) - SOLO si NO es aborto/muerte
                $codigoCria = trim($row[1] ?? '');
                $sexoCriaRaw = strtoupper(trim($row[3] ?? ''));
                $sexoCria = $sexoCriaRaw === 'M' ? 'macho' : ($sexoCriaRaw === 'H' ? 'hembra' : '');
                $criaExistente = null; // Animal ya registrado en BD (modalidad cría existente)

                if (!$esAbortoOMuerte) {
                    if (empty($codigoCria)) {
                        $this->errores[] = "Fila {$fila}: Codigo Cria vacio (requerido para partos normales)";
                        continue;
                    }
                    if (empty($sexoCria)) {
                        $this->errores[] = "Fila {$fila}: Sexo Cria invalido (debe ser M o H)";
                        continue;
                    }

                    // Verificar si la cría ya existe en BD (modalidad cría existente)
                    $criaEnBD = Animal::where('codigo', $codigoCria)
                        ->where('id_predio', $this->predio_id)
                        ->first();

                    if ($criaEnBD) {
                        // Advertencia si el sexo no coincide, pero no bloquear
                        if ($criaEnBD->sexo !== $sexoCria) {
                            $this->errores[] = "Fila {$fila}: Advertencia - Sexo en Excel ({$sexoCriaRaw}) no coincide con el registrado para '{$codigoCria}' ({$criaEnBD->sexo}). Se usa el animal existente.";
                        }

                        // Verificar que no exista ya un parto con esta madre y esta cría
                        $partoYaExiste = PartoAnimal::where('id_animal', $vaca->id_animal)
                            ->where('id_cria', $criaEnBD->id_animal)
                            ->exists();

                        if ($partoYaExiste) {
                            $this->duplicados[] = "Fila {$fila}: Parto DUPLICADO - Vaca '{$codigoMadre}' ya tiene registrado un parto con la cria '{$codigoCria}'";
                            continue;
                        }

                        $criaExistente = $criaEnBD;
                    }
                    // Si $criaEnBD es null -> flujo normal: crear nueva cría en la transacción
                }

                // 6. VALIDAR ESTADO ACTUAL (columna 9)
                $estadoActualRaw = trim($row[9] ?? '');
                if (empty($estadoActualRaw)) {
                    $this->errores[] = "Fila {$fila}: Campo 'Estado actual' vacio (requerido)";
                    continue;
                }

                $estadoVida = match(strtolower($estadoActualRaw)) {
                    'está en el predio', 'esta en el predio' => 1,
                    'no está en el predio', 'no esta en el predio' => 4,
                    default => null
                };

                if ($estadoVida === null) {
                    $this->errores[] = "Fila {$fila}: Estado actual invalido '{$estadoActualRaw}'";
                    continue;
                }

                // 7. PROCESAR PADRE (columna 5)
                $padreRaw = trim($row[5] ?? '');
                $id_padre = null;
                $padre_nombre = null;

                if (!empty($padreRaw)) {
                    // Extraer código si viene con formato "codigo - nombre"
                    $codigoPadre = explode(' - ', $padreRaw)[0];

                    $padre = Animal::where('codigo', $codigoPadre)
                        ->where('id_predio', $this->predio_id)
                        ->where('sexo', 'macho')
                        ->first();

                    if ($padre) {
                        $id_padre = $padre->id_animal;
                    } else {
                        // Si no existe en BD, guardarlo como nombre (padre externo)
                        $padre_nombre = $padreRaw;
                    }
                }

                // 8. TRANSACCIÓN
                DB::beginTransaction();

                try {
                    // Crear parto
                    $tipoParto = $esAbortoOMuerte ? $tipoEvento : 'Parto';

                    $parto = PartoAnimal::create([
                        'id_animal'     => $vaca->id_animal,
                        'fecha_parto'   => $fechaParto,
                        'tipo_parto'    => ucfirst(strtolower($tipoParto)),
                        'id_padre'      => $id_padre,
                        'padre_nombre'  => $padre_nombre,
                        'observaciones' => trim($row[11] ?? ''),
                    ]);

                    // Crear o vincular cría si NO es aborto/muerte
                    if (!$esAbortoOMuerte && !empty($codigoCria)) {
                        if ($criaExistente) {
                            // MODALIDAD: cría ya existe en BD
                            $cria = $criaExistente;

                            // Actualizar madre si el campo está vacío
                            if (empty($cria->madre)) {
                                $cria->madre = $vaca->id_animal;
                                $cria->save();
                            }
                        } else {
                            // MODALIDAD: crear nueva cría
                            $cria = Animal::create([
                                'id_predio'           => $this->predio_id,
                                'codigo'              => $codigoCria,
                                'nombre'              => trim($row[2] ?? ''),
                                'sexo'                => $sexoCria,
                                'fecha_nacimiento'    => $fechaParto,
                                'raza'                => trim($row[6] ?? ''),
                                'hierro'              => !empty($row[7]) ? trim($row[7]) : null,
                                'color'               => !empty($row[8]) ? trim($row[8]) : null,
                                'id_madre'            => $vaca->id_animal,
                                'id_padre'            => $id_padre,
                                'estado_productivo_id' => $sexoCria === 'hembra' ? 7 : 15, // CH o CM
                                'estado_vida'         => $estadoVida,
                                'fecha_ingreso_hato'  => $fechaParto,
                                'created_by'          => auth()->id(),
                            ]);
                        }

                        // Actualizar parto con id_cria
                        $parto->update(['id_cria' => $cria->id_animal]);

                        // Tabla pivote
                        DB::table('parto_cria')->insert([
                            'id_parto'   => $parto->id_parto,
                            'id_cria'    => $cria->id_animal,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    // NO actualizar estado_reproductivo porque es historial

                    DB::commit();
                    $this->exitosos++;

                } catch (\Exception $e) {
                    DB::rollBack();
                    $this->errores[] = "Fila {$fila}: Error al guardar - {$e->getMessage()}";
                }

            } catch (\Exception $e) {
                $this->errores[] = "Fila {$fila}: Error critico - {$e->getMessage()}";
                continue;
            }
        }
    }

    public function getErrores()
    {
        return $this->errores;
    }

    public function getExitosos()
    {
        return $this->exitosos;
    }

    public function getDuplicados()
    {
        return $this->duplicados;
    }
}
