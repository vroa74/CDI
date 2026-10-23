# Problemas detectados en CCDI

**Sitio revisado:** `http://ccditmp.ask.me/`  
**Fecha:** 23 de octubre de 2026  
**Método:** solicitudes HTTP GET públicas y revisión estática del repositorio local. No inicié sesión, no envié formularios, no accedí a datos de usuarios ni modifiqué la base de datos. No pude hacer inspección visual con Playwright porque no había navegador disponible en la sesión.

Los hallazgos del sitio se observaron directamente en las respuestas públicas. Los marcados como revisión local dependen de que la versión desplegada corresponda al código del espacio de trabajo.

## Hallazgos prioritarios

### 1. El sitio y el formulario de acceso usan HTTP sin cifrado

**Prioridad: Crítica**  
**Estado: requiere despliegue.** No hay configuración de Nginx, TLS ni certificados versionada en este repositorio. Debe configurarse HTTPS y la redirección HTTP a HTTPS en el servidor antes de habilitar `SESSION_SECURE_COOKIE=true` en el entorno de producción. El middleware agregado emite HSTS únicamente en respuestas HTTPS para no anunciar una política insegura sobre HTTP.
**Evidencia:** `/` y `/login` responden por HTTP; el formulario envía credenciales a `http://ccditmp.ask.me/login`. No se observó redirección a HTTPS. La cookie de sesión observada tampoco incluía el atributo `Secure`.

Las credenciales y la sesión pueden quedar expuestas o ser alteradas por alguien con capacidad de interceptar el tráfico entre el usuario y el servidor.

**Reparación:** habilitar TLS en el servidor y redirigir todo HTTP a HTTPS. Configurar cookies de sesión seguras (`Secure`, `HttpOnly`, `SameSite`) y HSTS después de confirmar que HTTPS funciona en todos los subdominios que deban cubrirse.

### 2. El HTML público activa un registrador de consola de Laravel Boost

**Prioridad: Alta**  
**Estado: corregido.** Se agregó `config/boost.php`: Boost solo se habilita en entorno local y el observador de registros del navegador queda desactivado.
**Evidencia observada en `/`:** el HTML contiene el script `browser-logger-active`, que intercepta `console.log`, `debug`, `info`, `error`, `warn` y `table`, y envía los datos a `http://ccditmp.ask.me/_boost/browser-logs`. Adjunta la URL visitada y el user-agent; los errores pueden incluir mensajes y trazas.

En el repositorio hay pantallas que imprimen datos de formularios y valores en consola. Si esa versión está desplegada, el registrador podría recopilar datos de páginas y usuarios autenticados. El envío también se hace por HTTP.

**Reparación:** retirar o desactivar la instrumentación de navegador de Boost en producción. Revisar el endpoint de recepción y los registros ya recopilados, limitando su acceso y retención.

### 3. Los PDF de servicios e inventario no requieren autenticación

**Prioridad: Alta**  
**Estado: corregido.** Las rutas PDF ahora requieren sesión autenticada, correo verificado y el permiso administrativo `view-reports`.
**Evidencia en el repositorio local:** `routes/web.php:38-46` declara las rutas PDF fuera del grupo autenticado. Los controladores cargan registros por ID y transmiten los reportes (`ServiceController.php:96-157`, `InventoryController.php:97-111`). No abrí estos PDF para evitar consultar información de producción.

Los reportes pueden incluir nombres, oficinas, números de serie, detalles de servicios y fotos. Los IDs numéricos podrían probarse para consultar otros registros.

**Reparación:** exigir autenticación y autorización por registro. Si se necesita compartir un PDF externamente, usar enlaces firmados con expiración y limitar la información incluida.

### 4. El límite de intentos de login no está conectado a la ruta personalizada

**Prioridad: Alta**  
**Estado: corregido.** La ruta POST personalizada `/login` aplica `throttle:login`.
**Evidencia en el repositorio local:** `FortifyServiceProvider.php:39-43` declara cinco intentos por minuto, pero `routes/web.php:26-28` no aplica `throttle:login` al POST `/login`. Además, el proveedor llama `Fortify::ignoreRoutes()`, por lo que las rutas propias de Fortify no aplican el límite a esta ruta.

Esto deja la ruta de acceso expuesta a intentos repetidos de contraseña.

**Reparación:** aplicar un limitador al POST personalizado y verificar que limite por cuenta e IP, con respuestas que no revelen si el correo o RFC existe.

## Otros problemas observados en el sitio

### 5. La portada no tiene contenido principal

**Prioridad: Media**  
**Estado: corregido.** La ruta pública `/` redirige al acceso, por lo que ya no muestra una portada vacía.
**Evidencia observada:** `/` responde con estado 200 y título `CCDI`, pero el contenido visible extraído del HTML es únicamente `CCDI` y el enlace «Iniciar sesión». El elemento `<main>` está vacío en la vista local `resources/views/welcome.blade.php`.

Si la portada debe orientar a los usuarios o presentar el sistema, ahora no ofrece información ni navegación salvo el acceso.

**Reparación:** agregar contenido y navegación esperados para la portada, o redirigirla al acceso si el sistema no debe tener una página pública.

### 6. No hay recuperación de contraseña accesible

**Prioridad: Media**  
**Estado: corregido.** Se registraron las rutas de recuperación de Fortify sin reactivar el registro público. El flujo incluye formularios en español y está cubierto por pruebas.
**Evidencia:** el login servido no muestra enlace para recuperar contraseña. En el repositorio local Fortify tiene sus rutas deshabilitadas (`FortifyServiceProvider.php:25`) y `routes/web.php` no define rutas `password.request` ni `password.email`. La vista solo mostraría el enlace si existe `password.request`.

Un usuario que olvide su contraseña no puede iniciar el flujo de restablecimiento por sí mismo.

**Reparación:** habilitar y configurar de forma segura el flujo de recuperación, incluido el correo saliente, o documentar el proceso administrativo para restablecer cuentas.

### 7. El favicon responde vacío

**Prioridad: Baja**  
**Estado: corregido.** `public/favicon.ico` fue reemplazado por un icono válido generado a partir del recurso de marca existente.
**Evidencia observada:** `GET /favicon.ico` responde 200 con `Content-Type: image/x-icon`, pero el cuerpo tiene cero bytes.

**Reparación:** publicar un favicon válido y apuntar a él desde el layout.

### 8. Faltan cabeceras HTTP de seguridad

**Prioridad: Media**  
**Estado: parcialmente corregido.** El middleware global agrega `Content-Security-Policy`, `Permissions-Policy`, `Referrer-Policy`, `X-Content-Type-Options` y `X-Frame-Options`. HSTS se envía solamente bajo HTTPS. Las cabeceras para recursos estáticos deben configurarse también en Nginx.
**Evidencia observada en `/login`:** no aparecieron `Strict-Transport-Security`, `Content-Security-Policy`, `X-Content-Type-Options`, `X-Frame-Options` ni `Referrer-Policy` en la respuesta.

La falta de estas cabeceras deja sin defensas adicionales ante algunos ataques de contenido, encuadre de páginas y filtración de referencias. HSTS solo tendrá efecto después de habilitar HTTPS.

**Reparación:** configurar cabeceras adecuadas en el servidor o middleware; definir CSP considerando que la aplicación actualmente incluye scripts en línea.

### 9. El servidor expone versiones de software en las cabeceras

**Prioridad: Baja**  
**Estado: requiere despliegue.** La supresión de `Server` y `X-Powered-By` se configura en Nginx y PHP, que no están incluidos en este repositorio.
**Evidencia observada:** las respuestas anuncian `nginx/1.22.0` y `PHP/8.3.18` mediante las cabeceras `Server` y `X-Powered-By`.

Esto facilita identificar tecnologías y versiones del servidor.

**Reparación:** ocultar o reducir los detalles de versión en las cabeceras y mantener Nginx, PHP y dependencias actualizados.

## Hallazgo que depende de los datos existentes

### 10. Usuarios sin CURP no podrán guardar ediciones desde el administrador

**Prioridad: Media**  
**Estado: descartado.** La migración posterior `2025_07_22_110913_update_curp_field_length_in_users_table.php` hace obligatoria la CURP; el informe original solo consideró la migración inicial.
**Evidencia en el repositorio local:** la migración inicial permite `users.curp` nula (`database/migrations/0001_01_01_000000_create_users_table.php:18`), pero `app/Livewire/Admin/Usuarios/Index.php:76-80, 150-186` exige CURP al crear y editar usuarios. No consulté los datos para confirmar si existen cuentas sin CURP.

Si hay usuarios sin CURP, no podrán guardar cambios a otros campos hasta asignarles una.

**Reparación:** decidir si la CURP debe ser obligatoria para todas las cuentas y, según esa decisión, permitir conservar el valor vacío al editar o regularizar cuentas antes de desplegar el cambio.

## Verificación pendiente

La inspección de este informe no incluyó autenticación, envío de formularios, carga de datos privados, pruebas de explotación, revisión visual en navegador ni comprobación de la base de datos. Las correcciones locales se validaron con 15 pruebas focalizadas; los puntos de Nginx/PHP deben aplicarse y verificarse en el servidor desplegado.
