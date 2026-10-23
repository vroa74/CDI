<laravel-boost-guidelines>
=== reglas base ===

# Pruebas y archivos de pruebas
Todos los archivos generados para las pruebas estaran ubicados en
la carpeta /pruebas si execpcion.

# Guía de Laravel Boost

Las directrices de Laravel Boost están especialmente curadas por los mantenedores de Laravel para esta aplicación. Estas reglas deben seguirse de cerca para asegurar la mejor experiencia al construir aplicaciones Laravel.

## Contexto fundamental

Esta aplicación es un proyecto Laravel y los principales paquetes del ecosistema con sus versiones están listados a continuación. Eres un experto en todos ellos. Asegúrate de cumplir con estos paquetes y versiones específicos.

- php - 8.3
- laravel/fortify (FORTIFY) - v1
- laravel/framework (LARAVEL) - v13
- laravel/prompts (PROMPTS) - v0
- laravel/sanctum (SANCTUM) - v4
- livewire/livewire (LIVEWIRE) - v3
- laravel/boost (BOOST) - v2
- laravel/mcp (MCP) - v0
- laravel/pail (PAIL) - v1
- laravel/pint (PINT) - v1
- pestphp/pest (PEST) - v4
- phpunit/phpunit (PHPUNIT) - v12
- tailwindcss (TAILWINDCSS) - v3

## Activación de habilidades

Este proyecto tiene habilidades específicas de dominio disponibles en `**/skills/**`. DEBES activar la habilidad relevante siempre que trabajes en ese dominio; no esperes hasta estar atascado.

## Convenciones

- Debes seguir todas las convenciones de código existentes usadas en esta aplicación. Al crear o editar un archivo, revisa archivos hermanos para la estructura, el enfoque y la nomenclatura correctos.
- Todas las rutas, adiciones, anexiones y modificaciones deben seguir la guía de estilo observada en el proyecto.
- Usa nombres descriptivos para variables y métodos. Por ejemplo, `isRegisteredForDiscounts`, no `discount()`.
- Revisa si ya existe un componente reutilizable antes de escribir uno nuevo.
- Cuando se solicite una modificación de una ruta o de una acción específica, realiza únicamente esa acción solicitada y verifica que no repercuta en las demás acciones de esa misma ruta ni en ninguna otra ruta.
- Todas las vistas deben estar escritas en español.

## Guía de estilo del proyecto

- Idioma: todo texto visible en interfaz (títulos, etiquetas, mensajes, botones, validaciones y ayudas) debe estar en español.
- Rutas: usa rutas con nombre y `route()` para generar enlaces; conserva la convención actual de recursos y alias de nombres existentes.
- Controladores: mantén clases en `App\Http\Controllers` con sufijo `Controller` y nombres en PascalCase.
- Livewire: organiza componentes por dominio y acción (por ejemplo `App\Livewire\Service\Index`), con propiedades en camelCase y bindings `wire:model.live` cuando se requiera filtrado en tiempo real.
- Modelos y base de datos: respeta los nombres de columnas heredadas existentes (incluyendo `snake_case` y campos legacy como `id_s`, `F_serv`), sin renombrar campos salvo solicitud explícita.
- Blade: prioriza componentes reutilizables (`x-...`) antes de duplicar bloques; conserva estructuras existentes de layout y slots.
- Estilos: usa Tailwind CSS como primera opción, manteniendo coherencia visual con la paleta y patrones ya presentes en cada módulo.
- Coherencia visual: conserva el mismo estilo visual en todo el proyecto; reutiliza los patrones existentes en lugar de inventar variantes.
- Campos de contraseña: todo input de contraseña debe incluir el botón de mostrar/ocultar con el estilo del login (icono de ojo SVG, `text-gray-400 hover:text-gray-600`, posicionado a la derecha). En Livewire/Blade usa el componente `<x-password-toggle-button />` dentro de un contenedor `relative` con `x-data="{ show: false }"` y `x-bind:type="show ? 'text' : 'password'"` en el input.
- Mensajes de sesión: conserva claves estándar observadas (`message`, `error`) y textos claros en español.
- Comentarios: usa comentarios breves y útiles, preferentemente en español, solo cuando aporten contexto no obvio.

## Scripts de verificación

- No crees scripts de verificación ni uses tinker cuando las pruebas cubran esa funcionalidad y demuestren que funciona. Las pruebas unitarias y de características son más importantes.

## Protección de los datos de la base de datos (PRIORIDAD MÁXIMA)

- Protege los datos de la base de datos a toda costa; esta regla prevalece sobre cualquier otra.
- Nunca ejecutes comandos destructivos contra la base de datos real: `migrate:fresh`, `migrate:refresh`, `migrate:reset`, `db:wipe`, `db:seed` sobre datos existentes, `DROP`, `TRUNCATE` ni `DELETE`/`UPDATE` masivos o sin `WHERE`.
- No modifiques ni borres usuarios, servicios, inventarios u otros registros existentes sin aprobación explícita del usuario.
- Antes de cualquier cambio de datos o esquema, haz un respaldo y avisa al usuario.
- Usa solo consultas de solo lectura por defecto.
- Las pruebas deben correr únicamente con la base de datos de pruebas (SQLite `:memory:` de `phpunit.xml`), nunca con `RefreshDatabase` apuntando a MySQL real.
- No restaures ni sobrescribas respaldos (`sql/`) sin confirmación explícita.
- No cambies las credenciales ni la conexión de base de datos en `.env`.

## Estructura y arquitectura de la aplicación

- Mantente en la estructura de directorios existente; no crees nuevas carpetas base sin aprobación.
- No cambies las dependencias de la aplicación sin aprobación.

## Empaquetado de frontend

- Si el usuario no ve un cambio de frontend reflejado en la interfaz, puede significar que necesita ejecutar `npm run build`, `npm run dev` o `composer run dev`. Pregúntale.

## Archivos de documentación

- Solo debes crear archivos de documentación si el usuario lo solicita explícitamente.

## Respuestas

- Sé conciso en tus explicaciones: enfócate en lo importante en lugar de explicar detalles obvios.

=== reglas boost ===

# Laravel Boost

## Herramientas

- Laravel Boost es un servidor MCP con herramientas diseñadas específicamente para esta aplicación. Prefiere las herramientas de Boost sobre alternativas manuales como comandos de shell o lectura de archivos.
- Usa `database-query` para ejecutar consultas de solo lectura en la base de datos en lugar de escribir SQL crudo en tinker.
- Usa `database-schema` para inspeccionar la estructura de tablas antes de escribir migraciones o modelos.
- Usa `get-absolute-url` para resolver el esquema, dominio y puerto correctos de las URLs del proyecto. Siempre úsalo antes de compartir una URL con el usuario.
- Usa `browser-logs` para leer registros del navegador, errores y excepciones. Solo los registros recientes son útiles; ignora las entradas antiguas.

## Búsqueda de documentación (IMPORTANTE)

- Siempre usa `search-docs` antes de hacer cambios en el código. No omitas este paso. Devuelve documentación específica de la versión basada en los paquetes instalados.
- Pasa un arreglo `packages` para limitar los resultados cuando sepas qué paquetes son relevantes.
- Usa múltiples consultas amplias basadas en temas: `['rate limiting', 'routing rate limiting', 'routing']`. Espera que los resultados más relevantes aparezcan primero.
- No agregues nombres de paquetes a las consultas porque la información del paquete ya se comparte. Usa `test resource table`, no `filament 4 test resource table`.

### Sintaxis de búsqueda

1. Usa palabras para lógica AND automática: `rate limit` coincide con "rate" Y "limit".
2. Usa `"frases entre comillas"` para coincidencias exactas de posición: `"infinite scroll"` requiere palabras adyacentes en ese orden.
3. Combina palabras y frases para consultas mixtas: `middleware "rate limit"`.
4. Usa múltiples consultas para lógica OR: `queries=["authentication", "middleware"]`.

## Artisan

- Ejecuta comandos Artisan directamente desde la línea de comandos (por ejemplo, `php artisan route:list`). Usa `php artisan list` para descubrir comandos disponibles y `php artisan [command] --help` para revisar parámetros.
- Inspecciona rutas con `php artisan route:list`. Filtra con: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Lee valores de configuración usando notación de puntos: `php artisan config:show app.name`, `php artisan config:show database.default`. O lee archivos de configuración directamente en el directorio `config/`.

## Tinker

- Ejecuta PHP en el contexto de la aplicación para depuración y pruebas. No crees modelos sin aprobación del usuario; prefiere pruebas con fábricas. Prefiere comandos Artisan existentes en lugar de código tinker personalizado.
- Usa siempre comillas simples para evitar la expansión del shell: `php artisan tinker --execute 'Your::code();'`
  - Usa comillas dobles dentro de las cadenas PHP: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== reglas php ===

# PHP

- Siempre usa llaves `{}` para estructuras de control, incluso en cuerpos de una sola línea.
- Usa promoción de propiedades del constructor de PHP 8: `public function __construct(public GitHub $github) { }`. No dejes métodos `__construct()` vacíos sin parámetros a menos que el constructor sea privado.
- Usa declaraciones de tipo de retorno explícitas y tipos en los parámetros de todos los métodos: `function isAccessible(User $user, ?string $path = null): bool`
- Usa TitleCase para las claves de Enum: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefiere bloques PHPDoc sobre comentarios en línea. Solo agrega comentarios en línea para lógica excepcionalmente compleja.
- Usa definiciones de forma de arreglo (`array shape`) en los PHPDoc.

=== reglas de despliegue ===

# Despliegue

- Laravel se puede desplegar usando [Laravel Cloud](https://cloud.laravel.com/), que es la forma más rápida de desplegar y escalar aplicaciones Laravel.

=== reglas de pruebas ===

# Aplicación de pruebas

- Cada cambio debe ser probado programáticamente. Crea una prueba nueva o actualiza una prueba existente, luego ejecuta las pruebas afectadas para asegurarte de que pasen.
- Ejecuta la menor cantidad de pruebas necesaria para asegurar la calidad y la velocidad. Usa `php artisan test --compact` con un nombre de archivo o filtro específico.

=== reglas laravel/core ===

# Haz las cosas a la manera de Laravel

- Usa los comandos `php artisan make:` para crear nuevos archivos (por ejemplo, migraciones, controladores, modelos, jobs, etc.). Puedes listar los comandos disponibles con `php artisan list` y revisar los parámetros con `php artisan [command] --help`.
- Si vas a crear una clase PHP genérica, usa `php artisan make:class`.
- Pasa `--no-interaction` a todos los comandos Artisan para asegurarte de que no requieran entrada del usuario. También debes pasar las `--options` correctas para asegurar el comportamiento esperado.

### Creación de modelos

- Cuando crees nuevos modelos, crea factories y seeders útiles también. Pregunta al usuario si necesita algo más, usando `php artisan make:model --help`.

## APIs y recursos Eloquent

- Para APIs, usa por defecto recursos Eloquent API y versionado de API a menos que las rutas existentes no lo hagan; en ese caso, sigue la convención actual de la aplicación.

## Generación de URL

- Al generar enlaces a otras páginas, prefiere rutas con nombre y la función `route()`.

## Pruebas

- Al crear modelos para pruebas, usa las factories. Revisa si la factory tiene estados personalizados antes de configurar manualmente el modelo.
- Faker: usa métodos como `$this->faker->word()` o `fake()->randomDigit()`. Sigue las convenciones existentes respecto a si usas `$this->faker` o `fake()`.
- Al crear pruebas, usa `php artisan make:test [options] {name}` para una prueba de características y pasa `--unit` para una prueba unitaria. La mayoría de las pruebas deben ser de características.

## Error de Vite

- Si recibes un error `Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest`, puedes ejecutar `npm run build` o preguntar al usuario que ejecute `npm run dev` o `composer run dev`.

=== reglas livewire/core ===

# Livewire

- Livewire permite construir interfaces dinámicas y reactivas en PHP sin escribir JavaScript.
- Puedes usar Alpine.js para interacciones del lado del cliente en lugar de frameworks JavaScript.
- Mantén el estado en el servidor para que la interfaz refleje los datos. Valida y autoriza en las acciones como lo harías en solicitudes HTTP.

=== reglas pint/core ===

# Formateador Laravel Pint

- Si has modificado archivos PHP, debes ejecutar `vendor/bin/pint --dirty --format agent` antes de finalizar los cambios para asegurar que tu código coincida con el estilo del proyecto.
- No ejecutes `vendor/bin/pint --test --format agent`; simplemente ejecuta `vendor/bin/pint --format agent` para corregir problemas de formato.

=== reglas pest/core ===

## Pest

- Este proyecto usa Pest para pruebas. Crea pruebas con `php artisan make:test --pest {name}`.
- El argumento `{name}` no debe incluir el directorio de la suite de pruebas. Usa `php artisan make:test --pest SomeFeatureTest` en lugar de `php artisan make:test --pest Feature/SomeFeatureTest`.
- Ejecuta pruebas con `php artisan test --compact` o con el filtro `php artisan test --compact --filter=testName`.
- NO elimines pruebas sin aprobación.

</laravel-boost-guidelines>
