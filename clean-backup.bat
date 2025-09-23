@echo off
echo Este script eliminara la carpeta de respaldo vendors-backup
echo ADVERTENCIA: Asegurate de haber verificado que la aplicacion funciona correctamente.
echo.
set /p continuar=Deseas continuar? (S/N):

if /i "%continuar%"=="S" (
    echo Eliminando carpeta vendors-backup...
    rmdir /s /q vendors-backup
    echo Carpeta eliminada.
) else (
    echo Operacion cancelada.
)

pause
