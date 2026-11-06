<?php

require dirname(__DIR__).'/vendor/autoload.php';

$projectPath = dirname(__DIR__);
$configuredCachePath = $_SERVER['APP_CONFIG_CACHE'] ?? $_ENV['APP_CONFIG_CACHE'] ?? getenv('APP_CONFIG_CACHE');
$cachePaths = [$projectPath.'/bootstrap/cache/config.php'];

if (is_string($configuredCachePath) && $configuredCachePath !== '') {
    $cachePaths[] = $configuredCachePath;
    $cachePaths[] = $projectPath.'/'.$configuredCachePath;
}

foreach ($cachePaths as $cachePath) {
    if (is_file($cachePath)) {
        throw new RuntimeException('PRUEBAS BLOQUEADAS: hay configuración cacheada de Laravel. No se ha iniciado la aplicación. Se requiere revisión y autorización explícita del usuario; no limpiar la caché automáticamente.');
    }
}
