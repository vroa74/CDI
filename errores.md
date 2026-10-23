# Fallos corregidos de la revisión del sistema

Revisión estática del código realizada el 23 de octubre de 2026. No se consultó ni modificó la base de datos. Las correcciones se validaron con pruebas automatizadas focalizadas; este documento no representa una auditoría exhaustiva de toda la aplicación.

## 1. Los reportes PDF se pueden consultar sin iniciar sesión

**Prioridad: Alta**

**Estado: corregido.** Las rutas de generación de PDF se integraron al grupo protegido con `auth:sanctum`, sesión de Jetstream y verificación de correo. Además, el permiso `view-reports` limita su consulta a administradores (`tipo = 1`):

- `GET /service-pdf/{id}`
- `GET /service-cal-pdf/{id}`
- `GET /service-details-pdf/{id}`
- `GET /inventory-pdf/{id}`

Las rutas ya no permiten consultar documentos por ID sin una sesión autenticada y con correo verificado, ni a usuarios sin permisos administrativos. Los documentos incluyen información de personas, oficinas, equipos, números de serie y, en el reporte detallado de servicio, fotos.

**Ubicación:** `routes/web.php`; `app/Providers/AppServiceProvider.php`.

## 2. El inicio de sesión no aplica el límite de intentos configurado

**Prioridad: Alta**

**Estado: corregido.** La ruta POST personalizada `/login` usa ahora el middleware `throttle:login`, que aplica el límite de cinco intentos por minuto configurado en `FortifyServiceProvider`.

El middleware utiliza la clave configurada por el limitador, que combina el usuario con la dirección IP.

**Ubicación:** `routes/web.php`; `app/Providers/FortifyServiceProvider.php`.

## 3. La edición administrativa exige CURP incluso para usuarios existentes que no la tienen

**Prioridad: Media**

**Estado: descartado.** La migración posterior `2025_07_22_110913_update_curp_field_length_in_users_table.php` cambia la columna sin `nullable()`, por lo que la base de datos final ya impide cuentas con CURP nula. Mantener la CURP como obligatoria al crear y editar es coherente con el esquema actual.

El hallazgo se originó al revisar solamente la migración inicial; no representaba el esquema completo de la aplicación.

**Ubicación:** `database/migrations/0001_01_01_000000_create_users_table.php:18`; `app/Livewire/Admin/Usuarios/Index.php:76-80, 150-186`.

## Alcance

Los hallazgos se identificaron mediante lectura de rutas, controladores, vistas, validaciones y migraciones. No se verificó su explotación en un entorno desplegado ni se inspeccionaron datos reales de producción.
