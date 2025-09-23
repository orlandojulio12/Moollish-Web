<?php
/**
 * Script para limpiar archivos no utilizados en public_html/assets/vendors
 *
 * Este script identifica los archivos que están siendo utilizados basado en una lista
 * predefinida y mueve el resto a una carpeta de respaldo.
 *
 * Uso: php clean-vendors.php
 */

// Lista de archivos esenciales que se utilizan en la aplicación
$usedFiles = [
    // CSS esenciales
    'vendors.min.css',
    'vendors.min.css.map',
    'dataTables.bs5.min.css',
    'dataTables.bs5.min.css.map',

    // JS esenciales
    'vendors.min.js',
    'vendors.min.js.map',
    'dataTables.min.js',
    'dataTables.min.js.map',
    'dataTables.bs5.min.js',
    'dataTables.bs5.min.js.map',

    // Agrega aquí otros archivos que necesites conservar
];

// Carpeta donde se moverán los archivos no utilizados
$backupFolder = __DIR__ . '/vendors-backup';

// Carpeta de vendors
$vendorsCssFolder = __DIR__ . '/public_html/assets/vendors/css';
$vendorsJsFolder = __DIR__ . '/public_html/assets/vendors/js';
$vendorsFontsFolder = __DIR__ . '/public_html/assets/vendors/fonts';

// Crea la carpeta de respaldo si no existe
if (!file_exists($backupFolder)) {
    mkdir($backupFolder, 0755, true);
    mkdir($backupFolder . '/css', 0755, true);
    mkdir($backupFolder . '/js', 0755, true);
    mkdir($backupFolder . '/fonts', 0755, true);
}

// Procesa la carpeta CSS
echo "Procesando archivos CSS...\n";
procesarCarpeta($vendorsCssFolder, $backupFolder . '/css', $usedFiles);

// Procesa la carpeta JS
echo "Procesando archivos JS...\n";
procesarCarpeta($vendorsJsFolder, $backupFolder . '/js', $usedFiles);

// Procesa la carpeta de fonts si existe
if (file_exists($vendorsFontsFolder)) {
    echo "Procesando archivos de fonts...\n";
    procesarCarpeta($vendorsFontsFolder, $backupFolder . '/fonts', $usedFiles);
}

echo "\nProceso completado!\n";
echo "Los archivos no utilizados se han movido a la carpeta 'vendors-backup'.\n";
echo "Por favor, revisa esta carpeta antes de eliminar los archivos originales.\n";
echo "Si todo está en orden, puedes eliminar la carpeta 'vendors-backup'.\n";

/**
 * Función para procesar una carpeta, mover archivos no utilizados al respaldo
 */
function procesarCarpeta($carpetaOrigen, $carpetaDestino, $archivosUtilizados) {
    if (!file_exists($carpetaOrigen)) {
        echo "  La carpeta $carpetaOrigen no existe.\n";
        return;
    }

    $archivos = scandir($carpetaOrigen);
    $contadorMovidos = 0;

    foreach ($archivos as $archivo) {
        // Ignora los directorios y archivos especiales
        if ($archivo === '.' || $archivo === '..' || $archivo === '.gitkeep') {
            continue;
        }

        // Si el archivo no está en la lista de utilizados, muévelo
        if (!in_array($archivo, $archivosUtilizados)) {
            $origen = $carpetaOrigen . '/' . $archivo;
            $destino = $carpetaDestino . '/' . $archivo;

            echo "  Moviendo archivo no utilizado: $archivo\n";

            // Copia el archivo a la carpeta de respaldo
            if (copy($origen, $destino)) {
                // Si deseas eliminar el archivo original, descomenta la siguiente línea
                // unlink($origen);
                $contadorMovidos++;
            } else {
                echo "  Error al mover el archivo: $archivo\n";
            }
        }
    }

    echo "  Se han movido $contadorMovidos archivos.\n";
}

?>
