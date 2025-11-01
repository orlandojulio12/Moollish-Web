<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Collection;
use App\Models\Animal;
use App\Models\Palpacion;
use App\Models\Veterinario;
use App\Models\EstadoReproductivo;
use App\Models\AnimalEstadoReproductivo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PalpacionesImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
{
    protected $predio_id;
    protected $errores = [];
    protected $duplicados = [];
    protected $exitosos = 0;

    public function __construct($predio_id)
    {
        $this->predio_id = $predio_id;
    }

    public function headingRow(): int
    {
        return 12;
    }

    public function collection(Collection $rows)
    {
        // Debug: Ver qué columnas tiene realmente
        if ($rows->isNotEmpty()) {
            $primeraFila = $rows->first();
            Log::info('Columnas disponibles: ' . json_encode(array_keys($primeraFila->toArray())));
        }

        // Filtrar filas vacías
        $rows = $rows->filter(function ($row) {
            $codigoVaca = trim($row['codigo_vaca'] ?? '');
            return !empty($codigoVaca);
        });

        Log::info("Total de filas después del filtro: " . $rows->count());

        if ($rows->isEmpty()) {
            $this->errores[] = "El archivo está vacío o no tiene datos válidos";
            return;
        }

        foreach ($rows as $index => $row) {
            $fila = $index + 13;

            try {
                // 1. Código de vaca - mantener como texto tal cual viene
                $codigoVaca = trim($row['codigo_vaca'] ?? '');

                Log::info("Procesando fila {$fila}, código: '{$codigoVaca}'");

                if (empty($codigoVaca)) {
                    $this->errores[] = "Fila {$fila}: Código Vaca está vacío";
                    continue;
                }

                $vaca = Animal::where('codigo', $codigoVaca)
                    ->where('id_predio', $this->predio_id)
                    ->where('sexo', 'hembra')
                    ->first();
                if (!$vaca) {
                    $this->errores[] = "Fila {$fila}: Código Vaca '{$codigoVaca}' no existe o no es hembra";
                    continue;
                }

                // 2. Validar y parsear fecha de palpación (manejar múltiples formatos de Excel)
                $fechaPalpacion = $row['fecha_palpacion'] ?? '';

                Log::info("Fila {$fila}, fecha recibida: tipo=" . gettype($fechaPalpacion) . ", valor='{$fechaPalpacion}'");

                if (empty($fechaPalpacion)) {
                    $this->errores[] = "Fila {$fila}: Código Vaca '{$codigoVaca}' - Fecha de palpación vacía";
                    continue;
                }

                // Parsear fecha (manejar múltiples formatos de Excel)
                try {
                    // Si es un objeto DateTime de Excel
                    if ($fechaPalpacion instanceof \DateTime) {
                        $fecha = $fechaPalpacion->format('Y-m-d');
                        Log::info("Fila {$fila}, fecha parseada desde DateTime: {$fecha}");
                    }
                    // Si es un número de Excel (días desde 1900)
                    elseif (is_numeric($fechaPalpacion)) {
                        $fecha = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($fechaPalpacion)->format('Y-m-d');
                        Log::info("Fila {$fila}, fecha parseada desde número Excel: {$fecha}");
                    }
                    // Si es un string, intentar parsearlo
                    else {
                        $fechaStr = trim($fechaPalpacion);
                        // Intentar formato dd/mm/yyyy primero
                        try {
                            $fecha = Carbon::createFromFormat('d/m/Y', $fechaStr)->format('Y-m-d');
                            Log::info("Fila {$fila}, fecha parseada desde string dd/mm/yyyy: {$fecha}");
                        } catch (\Exception $e) {
                            // Fallback: dejar que Carbon adivine el formato
                            $fecha = Carbon::parse($fechaStr)->format('Y-m-d');
                            Log::info("Fila {$fila}, fecha parseada con Carbon::parse: {$fecha}");
                        }
                    }
                } catch (\Exception $e) {
                    Log::error("Fila {$fila}, error parseando fecha: " . $e->getMessage());
                    $this->errores[] = "Fila {$fila}: Código Vaca '{$codigoVaca}' - Formato de fecha inválido '{$fechaPalpacion}' (use dd/mm/aaaa)";
                    continue;
                }

                // 3. Validar duplicado
                $palpacionExistente = Palpacion::where('id_animal', $vaca->id_animal)
                    ->whereDate('fecha', $fecha)
                    ->first();
                if ($palpacionExistente) {
                    $this->duplicados[] = "Fila {$fila}: Código Vaca '{$codigoVaca}' - Ya existe una palpación en la fecha {$fecha}";
                    continue;
                }

                // 4. Validar resultado
                $resultado = trim($row['resultado'] ?? '');
                $resultadosValidos = ['Preñada', 'Vacía'];
                if (!in_array($resultado, $resultadosValidos)) {
                    $this->errores[] = "Fila {$fila}: Código Vaca '{$codigoVaca}' - Resultado inválido '{$resultado}'. Valores permitidos: " . implode(', ', $resultadosValidos);
                    continue;
                }

                // 5. Validar diagnóstico (obligatorio si resultado es Vacía)
                $diagnostico = trim($row['diagnostico'] ?? '');
                $diagnosticosValidos = [
                    'Vacía ciclando',
                    'Vacía estática',
                    'Vacia normal',
                    'Cuerpo Luteo ovario derecho',
                    'Cuerpo Luteo ovario izquierdo',
                    'Folículo ovario derecho',
                    'Folículo ovario izquierdo',
                    'Quistes',
                    'indantilismo genital'
                ];
                if ($resultado === 'Vacía' && !in_array($diagnostico, $diagnosticosValidos)) {
                    $this->errores[] = "Fila {$fila}: Código Vaca '{$codigoVaca}' - Diagnóstico inválido o vacío para Vacía. Valores permitidos: " . implode(', ', $diagnosticosValidos);
                    continue;
                }
                if ($resultado === 'Preñada') {
                    $diagnostico = null;
                }

                // 6. Validar días de preñada
                $diasPrenada = trim($row['dias_de_prenada'] ?? '');
                if ($resultado === 'Preñada') {
                    if (!empty($diasPrenada) && (!is_numeric($diasPrenada) || $diasPrenada < 0 || $diasPrenada > 285)) {
                        $this->errores[] = "Fila {$fila}: Código Vaca '{$codigoVaca}' - Días de Preñada inválido (0-285)";
                        continue;
                    }
                    $diasPrenada = !empty($diasPrenada) ? (int) $diasPrenada : null;
                } else {
                    $diasPrenada = null;
                }

                // 7. Calcular parto proyectado
                $partoProyectado = null;
                if ($resultado === 'Preñada' && $diasPrenada !== null) {
                    $partoProyectado = Carbon::createFromFormat('Y-m-d', $fecha)
                        ->addDays(285 - $diasPrenada)
                        ->format('Y-m-d');
                }

                // 8. Validar veterinario
                $nombreVeterinario = trim($row['veterinario'] ?? '');
                if (empty($nombreVeterinario)) {
                    $this->errores[] = "Fila {$fila}: Código Vaca '{$codigoVaca}' - Veterinario está vacío";
                    continue;
                }

                // Buscar usuario veterinario por nombre exacto (ignorando mayúsculas/minúsculas)
                $veterinario = \App\Models\User::where('id_rol', 4)
                    ->whereRaw('LOWER(name) = ?', [mb_strtolower($nombreVeterinario)])
                    ->first();

                if (!$veterinario) {
                    $this->errores[] = "Fila {$fila}: Código Vaca '{$codigoVaca}' - Veterinario con nombre '{$nombreVeterinario}' no existe";
                    continue;
                }

                // Validar que el veterinario esté asociado al predio
                $asignadoAlPredio = \DB::table('predios_x_usuario')
                    ->where('id_usuario', $veterinario->id)
                    ->where('id_predio', $this->predio_id)
                    ->exists();

                if (!$asignadoAlPredio) {
                    $this->errores[] = "Fila {$fila}: Código Vaca '{$codigoVaca}' - El veterinario '{$nombreVeterinario}' no pertenece al predio seleccionado.";
                    continue;
                }


                // 9. Iniciar transacción
                DB::beginTransaction();

                // 10. Crear palpación
                $palpacion = Palpacion::create([
                    'id_animal' => $vaca->id_animal,
                    'fecha' => $fecha,
                    'resultado' => $resultado,
                    'diagnostico' => $diagnostico,
                    'dias_prenada' => $diasPrenada,
                    'parto_proyectado' => $partoProyectado,
                    'id_palpador' => $veterinario->id,
                ]);

                // 11. Actualizar estado reproductivo
                $nuevoEstadoReproductivo = $resultado === 'Preñada' ? EstadoReproductivo::PRENADA : EstadoReproductivo::VACIA;
                $vaca->estado_reproductivo_id = $nuevoEstadoReproductivo;
                $vaca->save();

                AnimalEstadoReproductivo::create([
                    'id_animal' => $vaca->id_animal,
                    'id_estado_reproductivo' => $nuevoEstadoReproductivo,
                    'fecha_inicio' => $fecha,
                    'fecha_fin' => null,
                ]);

                DB::commit();
                $this->exitosos++;
                Log::info("Fila {$fila}: Palpación registrada para vaca '{$codigoVaca}'");

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Error en fila {$fila}: " . $e->getMessage());
                $this->errores[] = "Fila {$fila}: Código Vaca '{$codigoVaca}' - Error: {$e->getMessage()}";
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