<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Collection;
use App\Models\Animal;
use App\Models\PartoAnimal;
use DB;

class PartosImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    protected $predio_id;
    protected $errores = [];
    protected $duplicados = [];
    protected $exitosos = 0;

    public function __construct($predio_id)
    {
        $this->predio_id = $predio_id;
    }

    public function collection(Collection $rows)
    {
        // Filtrar filas completamente vacías
        $rows = $rows->filter(function($row) {
            return !empty(array_filter($row->toArray()));
        });

        foreach ($rows as $index => $row) {
            $fila = $index + 2; // +2 porque header es fila 1
            
            try {
                // 1. Validar que el código de vaca existe y no está vacío
                $codigoVaca = trim($row['codigo_vaca'] ?? '');
                if (empty($codigoVaca)) {
                    continue; // Saltar filas sin código (filas vacías)
                }

                $vaca = Animal::where('codigo', $codigoVaca)
                    ->where('predio_id', $this->predio_id)
                    ->first();
                
                if (!$vaca) {
                    $this->errores[] = "Fila {$fila}: Código Madre '{$codigoVaca}' no existe en la base de datos";
                    continue;
                }
                
                // 2. Validar fecha de parto
                $fechaParto = $row['fecha_parto'] ?? null;
                if (empty($fechaParto)) {
                    $this->errores[] = "Fila {$fila}: Código Madre '{$codigoVaca}' - Fecha de parto vacía";
                    continue;
                }
                
                // 3. VALIDAR DUPLICADO: Mismo animal + misma fecha
                $partoExistente = PartoAnimal::where('id_animal', $vaca->id_animal)
                    ->whereDate('fecha_parto', $fechaParto)
                    ->first();
                
                if ($partoExistente) {
                    $this->duplicados[] = "Fila {$fila}: Código Madre '{$codigoVaca}' ({$vaca->nombre}) - Ya existe un parto registrado en la fecha {$fechaParto}";
                    continue;
                }
                
                // 4. Validar tipo de parto
                $tipoParto = trim($row['tipo_parto'] ?? 'Parto');
                $tiposValidos = ['Parto', 'Gemelar', 'Trillizo', 'Aborto', 'Muerte Fetal'];
                if (!in_array($tipoParto, $tiposValidos)) {
                    $this->errores[] = "Fila {$fila}: Código Madre '{$codigoVaca}' - Tipo de parto inválido '{$tipoParto}'. Valores permitidos: " . implode(', ', $tiposValidos);
                    continue;
                }
                
                // 5. Validar que la vaca esté preñada (solo para partos normales, no abortos)
                if (in_array($tipoParto, ['Parto', 'Gemelar', 'Trillizo'])) {
                    if (!$vaca->prenez || $vaca->prenez != 'Preñada') {
                        $this->errores[] = "Fila {$fila}: Código Madre '{$codigoVaca}' ({$vaca->nombre}) - No está preñada. Estado actual: " . ($vaca->prenez ?? 'Sin estado');
                        continue;
                    }
                }
                
                // 🔥 TRANSACCIÓN INDIVIDUAL POR CADA PARTO
                DB::beginTransaction();
                
                // 6. Determinar el padre
                $padre = null;
                $padreNombre = null;
                
                if (!empty($row['padre'])) {
                    // Buscar si es un código de animal existente
                    $toroExistente = Animal::where('codigo', $row['padre'])
                        ->where('predio_id', $this->predio_id)
                        ->where('sexo', 'Macho')
                        ->first();
                    
                    if ($toroExistente) {
                        $padre = $toroExistente->id_animal;
                    } else {
                        // Si no existe, guardarlo como nombre
                        $padreNombre = $row['padre'];
                    }
                }
                
                // 7. Crear el parto (sin id_cria aún)
                $parto = PartoAnimal::create([
                    'id_animal' => $vaca->id_animal,
                    'fecha_parto' => $fechaParto,
                    'tipo_parto' => $tipoParto,
                    'padre' => $padre,
                    'padre_nombre' => $padreNombre,
                    'observaciones' => $row['observaciones'] ?? null,
                ]);
                
                // 8. Crear crías si el tipo de parto lo requiere
                if (in_array($tipoParto, ['Parto', 'Gemelar', 'Trillizo'])) {
                    $numCrias = $tipoParto == 'Trillizo' ? 3 : ($tipoParto == 'Gemelar' ? 2 : 1);
                    $primeraCria = null;
                    
                    for ($i = 1; $i <= $numCrias; $i++) {
                        $codigoCria = trim($row["codigo_cria_{$i}"] ?? '');
                        
                        if (!empty($codigoCria)) {
                            // Validar que el código de cría no exista
                            $criaExistente = Animal::where('codigo', $codigoCria)
                                ->where('predio_id', $this->predio_id)
                                ->first();
                            
                            if ($criaExistente) {
                                DB::rollBack();
                                $this->errores[] = "Fila {$fila}: Código Madre '{$codigoVaca}' - El código de cría '{$codigoCria}' ya existe en el sistema";
                                continue 2; // Sale del for y del foreach
                            }
                            
                            // Crear el animal (cría)
                            $cria = Animal::create([
                                'codigo' => $codigoCria,
                                'nombre' => $row["nombre_cria_{$i}"] ?? "Cría {$i}",
                                'sexo' => $row["sexo_cria_{$i}"] ?? null,
                                'fecha_nacimiento' => $fechaParto,
                                'peso_nacimiento' => $row["peso_cria_{$i}"] ?? null,
                                'raza' => $row["raza_cria_{$i}"] ?? $vaca->raza,
                                'predio_id' => $this->predio_id,
                                'id_madre' => $vaca->id_animal,
                                'clasificacion' => 'Cría',
                            ]);
                            
                            // Guardar la primera cría
                            if ($i == 1) {
                                $primeraCria = $cria->id_animal;
                            }
                            
                            // Insertar en tabla pivote parto_cria
                            DB::table('parto_cria')->insert([
                                'id_parto' => $parto->id_parto,
                                'id_cria' => $cria->id_animal,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                    
                    // Actualizar el parto con la primera cría
                    if ($primeraCria) {
                        $parto->update(['id_cria' => $primeraCria]);
                    }
                }
                
                // 9. Actualizar estado de la vaca madre
                $vaca->update(['prenez' => 'Vacía']);
                
                DB::commit();
                $this->exitosos++;
                
                \Log::info("Fila {$fila}: Parto registrado exitosamente para vaca '{$codigoVaca}'");
                
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error("Error en fila {$fila}: " . $e->getMessage());
                $this->errores[] = "Fila {$fila}: Código Madre '{$codigoVaca}' - Error al procesar: {$e->getMessage()}";
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