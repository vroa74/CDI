<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Bootstrap\RegisterProviders;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    public function createApplication(): Application
    {
        $app = require dirname(__DIR__).'/bootstrap/app.php';

        if ($app->configurationIsCached()) {
            throw new RuntimeException('PRUEBAS BLOQUEADAS: configuración cacheada. Solicitar autorización al usuario antes de continuar.');
        }

        $this->traitsUsedByTest = class_uses_recursive(static::class);

        $app->beforeBootstrapping(RegisterProviders::class, static function (Application $app): void {
            $config = $app->make('config');

            if (! $app->environment('testing')
                || $config->get('database.default') !== 'sqlite'
                || $config->get('database.connections.sqlite.driver') !== 'sqlite'
                || $config->get('database.connections.sqlite.database') !== ':memory:'
                || ! empty($config->get('database.connections.sqlite.url'))) {
                throw new RuntimeException('PRUEBAS BLOQUEADAS: solo se admite SQLite :memory: en entorno testing. No se permite conectar a MySQL ni a bases persistentes.');
            }

            // Eliminar conexiones alternativas antes de los proveedores y RefreshDatabase.
            $config->set('database.connections', [
                'sqlite' => [
                    'driver' => 'sqlite',
                    'database' => ':memory:',
                    'prefix' => '',
                    'foreign_key_constraints' => true,
                ],
            ]);
        });

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
