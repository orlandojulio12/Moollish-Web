<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Illuminate\Support\Collection;
use App\Models\Animal;
use App\Models\MontaNatural;
use App\Models\EstadoProductivo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MontaImport implements ToCollection, WithHeadingRow, SkipsEmptyRows
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
        return 10;
    }

    public function collection(Collection $rows)
    {
        // Debug
        if ($rows->isNotEmpty()) {
            $primeraFila = $rows->first();
            Log::info('Columnas disponibles: ' . json_encode(array_keys($primeraFila->toArray())));
        }

        // Filtrar filas vacías
        $rows = $rows->filter(function($row) {
            $codigoVaca = trim($row['codigo_vaca'] ?? '');
            return !empty($codigoVaca);
        });

        Log::info("Total de filas después del filtro: " . $rows->count());

        if ($rows->isEmpty()) {
            $this->errores[] = "El archivo está vacío o no tiene datos válidos";
            return;
        }

        foreach ($rows as $index => $row) {
            $fila = $index + 11;

            try {
                // 1. Extraer y limpiar código de vaca (puede venir con formato "codigo - nombre")
                $codigoVacaCompleto = trim($row['codigo_vaca'] ?? '');
                $codigoVaca = $this->extraerCodigo($codigoVacaCompleto);
                
                Log::info("Procesando fila {$fila}, código vaca: '{$codigoVaca}'");

                if (empty($codigoVaca)) {
                    $this->errores[] = "Fila {$fila}: Código Vaca está vacío";
                    continue;
                }

                // Buscar vaca y validar
                $vaca = Animal::where('codigo', $codigoVaca)
                    ->where('id_predio', $this->predio_id)
                    ->where('sexo', 'hembra')
                    ->first();
                
                if (!$vaca) {
                    $this->errores[] = "Fila {$fila}: Código Vaca '{$codigoVaca}' no existe en este predio o no es hembra";
                    continue;
                }

                // Validar estado productivo de la vaca
                $estadosValidosVaca = [
                    EstadoProductivo::HEMBRA_LEVANTE,
                    EstadoProductivo::NOVILLA_VIENTRE,
                    EstadoProductivo::VACA_SECA,
                    EstadoProductivo::VACA_PARIDA,
                ];
                
                if (!in_array($vaca->estado_productivo_id, $estadosValidosVaca)) {
                    $this->errores[] = "Fila {$fila}: Código Vaca '{$codigoVaca}' - La vaca no está en un estado válido para monta natural (debe ser: Hembra Levante, Novilla Vientre, Vaca Seca o Vaca Parida)";
                    continue;
                }

                // 2. Extraer y limpiar código de toro
                $codigoToroCompleto = trim($row['codigo_toro'] ?? '');
                $codigoToro = $this->extraerCodigo($codigoToroCompleto);
                
                if (empty($codigoToro)) {
                    $this->errores[] = "Fila {$fila}: Código Vaca '{$codigoVaca}' - Código Toro está vacío";
                    continue;
                }

                // Buscar toro y validar
                $toro = Animal::where('codigo', $codigoToro)
                    ->where('id_predio', $this->predio_id)
                    ->where('sexo', 'macho')
                    ->first();
                
                if (!$toro) {
                    $this->errores[] = "Fila {$fila}: Código Vaca '{$codigoVaca}' - Código Toro '{$codigoToro}' no existe en este predio o no es macho";
                    continue;
                }

                // Validar estado productivo del toro
                $estadosValidosToro = [
                    EstadoProductivo::MACHO_LEVANTE,
                    EstadoProductivo::MACHO_CEBA,
                    EstadoProductivo::REPRODUCTOR_TORO,
                ];
                
                if (!in_array($toro->estado_productivo_id, $estadosValidosToro)) {
                    $this->errores[] = "Fila {$fila}: Código Vaca '{$codigoVaca}' - El toro '{$codigoToro}' no está en un estado válido para monta natural (debe ser: Macho Levante, Macho Ceba o Reproductor Toro)";
                    continue;
                }

                // 3. Validar y parsear fecha de monta
                $fechaMonta = $row['fecha_monta'] ?? '';
                
                Log::info("Fila {$fila}, fecha recibida: tipo=" . gettype($fechaMonta) . ", valor='{$fechaMonta}'");
                
                if (empty($fechaMonta)) {
                    $this->errores[] = "Fila {$fila}: Código Vaca '{$codigoVaca}' - Fecha de monta vacía";
                    continue;
                }
                
                // Parsear fecha
                try {
                    if ($fechaMonta instanceof \DateTime) {
                        $fecha = $fechaMonta->format('Y-m-d');
                    }
                    elseif (is_numeric($fechaMonta)) {
                        $fecha = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($fechaMonta)->format('Y-m-d');
                    }
                    else {
                        $fechaStr = trim($fechaMonta);
                        try {
                            $fecha = Carbon::createFromFormat('d/m/Y', $fechaStr)->format('Y-m-d');
                        } catch (\Exception $e) {
                            $fecha = Carbon::parse($fechaStr)->format('Y-m-d');
                        }
                    }
                } catch (\Exception $e) {
                    Log::error("Fila {$fila}, error parseando fecha: " . $e->getMessage());
                    $this->errores[] = "Fila {$fila}: Código Vaca '{$codigoVaca}' - Formato de fecha inválido '{$fechaMonta}' (use dd/mm/aaaa)";
                    continue;
                }

                // Validar que la fecha no sea futura
                if (Carbon::parse($fecha)->isFuture()) {
                    $this->errores[] = "Fila {$fila}: Código Vaca '{$codigoVaca}' - La fecha de monta no puede ser futura";
                    continue;
                }

                // 4. Validar duplicado
                $montaExistente = MontaNatural::where('id_vaca', $vaca->id_animal)
                    ->where('id_toro', $toro->id_animal)
                    ->whereDate('fecha_monta', $fecha)
                    ->first();
                
                if ($montaExistente) {
                    $this->duplicados[] = "Fila {$fila}: Código Vaca '{$codigoVaca}' - Ya existe una monta natural con el toro '{$codigoToro}' en la fecha {$fecha}";
                    continue;
                }

                // 5. Iniciar transacción
                DB::beginTransaction();

                // 6. Crear monta natural
                $monta = MontaNatural::create([
                    'id_vaca' => $vaca->id_animal,
                    'id_toro' => $toro->id_animal,
                    'fecha_monta' => $fecha,
                ]);

                DB::commit();
                $this->exitosos++;
                Log::info("Fila {$fila}: Monta natural registrada - Vaca '{$codigoVaca}' x Toro '{$codigoToro}'");

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Error en fila {$fila}: " . $e->getMessage());
                $this->errores[] = "Fila {$fila}: Código Vaca '{$codigoVaca}' - Error: {$e->getMessage()}";
                continue;
            }
        }
    }

    /**
     * Extraer código de animal desde formato "codigo - nombre" o solo "codigo"
     */
    private function extraerCodigo($cadena)
    {
        $cadena = trim($cadena);
        
        // Si contiene guión, tomar solo la primera parte
        if (strpos($cadena, ' - ') !== false) {
            $partes = explode(' - ', $cadena);
            return trim($partes[0]);
        }
        
        return $cadena;
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