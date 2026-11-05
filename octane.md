# Reporte de Laravel Octane

Fecha: 27 de octubre de 2026.

## Estado inicial

Se comprobó el estado con:

```bash
php artisan octane:status --no-interaction
```

Resultado inicial:

```text
INFO  Octane server is not running.
```

Tampoco había procesos activos de Octane, FrankenPHP, RoadRunner o Swoole.

## Configuración identificada

- El paquete `laravel/octane` está instalado en el proyecto.
- `config/octane.php` establece `frankenphp` como servidor predeterminado.
- No había variables `OCTANE_*` definidas en `.env`.
- El puerto local `8000` estaba disponible.
- Apache continúa atendiendo los puertos públicos `80` y `443`.

## Inicio de Octane

Octane se inició usando el usuario propietario del sitio para evitar problemas de permisos:

```bash
runuser -u melomelon.club_vroa74 -- php artisan octane:start --server=frankenphp --host=127.0.0.1 --port=8000 --no-interaction
```

Durante el primer inicio, Octane descargó automáticamente el binario de FrankenPHP. Después inició correctamente en:

```text
http://127.0.0.1:8000
```

Como el servicio se ejecuta sin TLS propio, FrankenPHP informó que HTTP/2 y HTTP/3 no se habilitan en el puerto local. Esto es esperado: Apache es quien recibe el tráfico HTTPS público.

## Verificación

Se verificó el estado:

```bash
php artisan octane:status --no-interaction
```

Resultado:

```text
INFO  Octane server is running.
```

También se solicitó la página local:

```bash
curl --silent --show-error --output /dev/null --write-out '%{http_code}\n' --max-time 15 http://127.0.0.1:8000/
```

Resultado:

```text
200
```

## Estado actual

Laravel Octane está activo y responde correctamente en `127.0.0.1:8000`.

No se modificó la configuración de Apache ni se configuró un proxy inverso. Por tanto, Apache sigue atendiendo el dominio público en los puertos `80` y `443`; para que el sitio público sea servido por Octane, Apache debe redirigir las solicitudes hacia `127.0.0.1:8000`.
