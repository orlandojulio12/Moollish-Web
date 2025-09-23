<?php
/**
 * Script para eliminar permanentemente los archivos no utilizados en la carpeta vendors
 *
 * ADVERTENCIA: Este script eliminará permanentemente los archivos que no estén en la lista
 * de archivos utilizados. Asegúrate de haber probado la aplicación después de ejecutar
 * clean-vendors.php y confirmar que todo funciona correctamente.
 *
 * Uso: php remove-unused-vendors.php
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

// Carpeta de vendors
$vendorsCssFolder = __DIR__ . '/public_html/assets/vendors/css';
$vendorsJsFolder = __DIR__ . '/public_html/assets/vendors/js';
$vendorsFontsFolder = __DIR__ . '/public_html/assets/vendors/fonts';

// Confirmación de seguridad
echo "ADVERTENCIA: Este script eliminará permanentemente los archivos no utilizados.\n";
echo "¿Estás seguro de que deseas continuar? (y/n): ";
$handle = fopen("php://stdin", "r");
$line = trim(fgets($handle));
if(strtolower($line) !== 'y'){
    echo "Operación cancelada.\n";
    exit;
}

// Procesa la carpeta CSS
echo "Eliminando archivos CSS no utilizados...\n";
eliminarArchivosNoUtilizados($vendorsCssFolder, $usedFiles);

// Procesa la carpeta JS
echo "Eliminando archivos JS no utilizados...\n";
eliminarArchivosNoUtilizados($vendorsJsFolder, $usedFiles);

// Procesa la carpeta de fonts si existe
if (file_exists($vendorsFontsFolder)) {
    echo "Eliminando archivos de fonts no utilizados...\n";
    eliminarArchivosNoUtilizados($vendorsFontsFolder, $usedFiles);
}

echo "\nProceso completado! Se han eliminado los archivos no utilizados.\n";

/**
 * Función para eliminar archivos no utilizados de una carpeta
 */
function eliminarArchivosNoUtilizados($carpeta, $archivosUtilizados) {
    if (!file_exists($carpeta)) {
        echo "  La carpeta $carpeta no existe.\n";
        return;
    }

    $archivos = scandir($carpeta);
    $contadorEliminados = 0;

    foreach ($archivos as $archivo) {
        // Ignora los directorios y archivos especiales
        if ($archivo === '.' || $archivo === '..' || $archivo === '.gitkeep') {
            continue;
        }

        // Si el archivo no está en la lista de utilizados, elimínalo
        if (!in_array($archivo, $archivosUtilizados)) {
            $rutaCompleta = $carpeta . '/' . $archivo;

            echo "  Eliminando archivo no utilizado: $archivo\n";

            if (unlink($rutaCompleta)) {
                $contadorEliminados++;
            } else {
                echo "  Error al eliminar el archivo: $archivo\n";
            }
        }
    }

    echo "  Se han eliminado $contadorEliminados archivos.\n";
}

?>
