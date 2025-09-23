<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PredioParametro;
use App\Models\Animal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class ActualizarTransicionesPredio extends Command
{
    protected $signature = 'predios:actualizar-transiciones';
    protected $description = 'Actualiza el estado productivo de los animales según la configuración de transiciones de cada predio';

    public function handle()
    {
        // Obtener todas las configuraciones de transiciones para predios
        $configuraciones = PredioParametro::all();

        foreach ($configuraciones as $config) {
            try {
                // Se actualizan los animales del predio que estén en el estado actual y cuyo tiempo (basado en su fecha de nacimiento) supere el umbral.
                $afectados = Animal::where('id_predio', $config->predio_id)
                    ->where('estado_productivo_id', $config->estado_actual_id)
                    ->whereRaw('DATEDIFF(CURDATE(), fecha_nacimiento) >= ?', [$config->dias_transicion])
                    ->update(['estado_productivo_id' => $config->estado_nuevo_id]);

                $this->info("Predio {$config->predio_id}: actualizados {$afectados} animales.");
            } catch (\Exception $e) {
                // Se captura la excepción y se muestra un mensaje de error sin detener el ciclo.
                $this->error("Error en predio {$config->predio_id}: " . $e->getMessage());
                // Opcional: puedes registrar el error en un log para un análisis posterior.
                Log::error("Error actualizando transiciones en predio {$config->predio_id}: " . $e->getMessage());
            }
        }
        return 0;
    }
}


