@echo off
setlocal
cd /d "%~dp0"

if not exist "storage\app\public\profile-photos\" (
    echo ERROR: No existe la carpeta storage\app\public\profile-photos.
    exit /b 1
)

if exist "public\storage\profile-photos\" (
    echo El almacenamiento publico ya expone las fotos de perfil.
) else (
    if exist "public\storage\" (
        echo ERROR: public\storage ya existe, pero no expone profile-photos.
        echo Revisa esa carpeta manualmente; no se modifico ni se borro.
        exit /b 1
    )

    echo Creando el enlace publico de almacenamiento de Laravel...
    php artisan storage:link --no-interaction
    if errorlevel 1 (
        echo ERROR: No se pudo crear el enlace publico de almacenamiento.
        exit /b 1
    )
)

if not exist "public\storage\profile-photos\" (
    echo ERROR: No se puede acceder a public\storage\profile-photos.
    exit /b 1
)

echo Las fotos de perfil estan disponibles en public\storage\profile-photos.
exit /b 0