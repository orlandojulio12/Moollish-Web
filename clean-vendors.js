/**
 * Script para limpiar archivos no utilizados en public_html/assets/vendors
 *
 * Este script identifica qué archivos están siendo usados en la aplicación
 * y mueve el resto a una carpeta de respaldo para que puedas verificar antes de eliminarlos.
 */

const fs = require('fs');
const path = require('path');
const { exec } = require('child_process');

// Lista de archivos que sabemos que son utilizados
const usedFiles = [
  // CSS esenciales
  'public_html/assets/vendors/css/vendors.min.css',
  'public_html/assets/vendors/css/dataTables.bs5.min.css',

  // JS esenciales
  'public_html/assets/vendors/js/vendors.min.js',
  'public_html/assets/vendors/js/dataTables.min.js',
  'public_html/assets/vendors/js/dataTables.bs5.min.js',

  // Si hay otros archivos que sabes que necesitas, agrégalos aquí
];

// Carpeta a la que se moverán los archivos no utilizados
const backupFolder = 'vendors-backup';

// Función para buscar archivos en los recursos del proyecto
function searchInProjectFiles(filePattern) {
  return new Promise((resolve, reject) => {
    const command = `grep -r --include="*.php" --include="*.js" "${filePattern}" resources/ routes/ public_html/`;
    exec(command, (error, stdout, stderr) => {
      if (stderr) {
        console.error(`Error al buscar ${filePattern}:`, stderr);
      }
      resolve(stdout ? true : false);
    });
  });
}

// Función principal
async function cleanVendors() {
  // Crear carpeta de respaldo si no existe
  if (!fs.existsSync(backupFolder)) {
    fs.mkdirSync(backupFolder);
    fs.mkdirSync(path.join(backupFolder, 'css'));
    fs.mkdirSync(path.join(backupFolder, 'js'));
    fs.mkdirSync(path.join(backupFolder, 'fonts'));
  }

  // Procesar carpeta CSS
  const cssFolder = 'public_html/assets/vendors/css';
  const cssFiles = fs.readdirSync(cssFolder);

  for (const file of cssFiles) {
    if (file === '.gitkeep') continue;

    const fullPath = path.join(cssFolder, file);
    const relativePath = fullPath;

    // Verificar si el archivo está en la lista de usados
    const isInUsedList = usedFiles.includes(relativePath);

    // Si no está en la lista, buscar su nombre en los archivos del proyecto
    if (!isInUsedList) {
      const fileName = file.replace('.min.css', '').replace('.min.css.map', '');
      const isUsed = await searchInProjectFiles(fileName);

      if (!isUsed) {
        console.log(`Moviendo archivo CSS no utilizado: ${file}`);
        fs.copyFileSync(fullPath, path.join(backupFolder, 'css', file));
        // Descomenta la siguiente línea para eliminar el archivo original
        // fs.unlinkSync(fullPath);
      }
    }
  }

  // Procesar carpeta JS
  const jsFolder = 'public_html/assets/vendors/js';
  const jsFiles = fs.readdirSync(jsFolder);

  for (const file of jsFiles) {
    if (file === '.gitkeep') continue;

    const fullPath = path.join(jsFolder, file);
    const relativePath = fullPath;

    // Verificar si el archivo está en la lista de usados
    const isInUsedList = usedFiles.includes(relativePath);

    // Si no está en la lista, buscar su nombre en los archivos del proyecto
    if (!isInUsedList) {
      const fileName = file.replace('.min.js', '').replace('.min.js.map', '');
      const isUsed = await searchInProjectFiles(fileName);

      if (!isUsed) {
        console.log(`Moviendo archivo JS no utilizado: ${file}`);
        fs.copyFileSync(fullPath, path.join(backupFolder, 'js', file));
        // Descomenta la siguiente línea para eliminar el archivo original
        // fs.unlinkSync(fullPath);
      }
    }
  }

  console.log('\nProceso completado!');
  console.log(`Los archivos no utilizados se han movido a la carpeta ${backupFolder}.`);
  console.log('Revisa esta carpeta antes de eliminar definitivamente los archivos.');
  console.log('Para eliminar los archivos originales, descomenta las líneas fs.unlinkSync() en el script.');
}

// Ejecutar el proceso
cleanVendors().catch(error => {
  console.error('Error:', error);
});
