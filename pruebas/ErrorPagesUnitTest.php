<?php

use Illuminate\Auth\GenericUser;
use Illuminate\Auth\SessionGuard;
use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Routing\RouteCollection;
use Illuminate\Routing\Router;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Session\ArraySessionHandler;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Facade;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

test('las páginas de error llevan al inicio según la sesión sin base de datos', function (int $status, bool $authenticated): void {
    $container = new Container;
    Container::setInstance($container);
    $container->bind('db', fn () => throw new LogicException('Acceso a base de datos prohibido.'));
    $provider = Mockery::mock(UserProvider::class);
    $session = new Store('error-page-session', new ArraySessionHandler(120));
    $session->start();
    $guard = new SessionGuard('web', $provider, $session, Request::create('http://localhost/no-existe'));
    if ($authenticated) {
        $session->put($guard->getName(), 81);
        $provider->shouldReceive('retrieveById')->with(81)->once()->andReturn(new GenericUser(['id' => 81]));
    } else {
        $provider->shouldNotReceive('retrieveById');
    }
    $auth = Mockery::mock(Illuminate\Contracts\Auth\Factory::class);
    $auth->shouldReceive('guard')->with('web')->once()->andReturn($guard);
    $container->instance(Illuminate\Contracts\Auth\Factory::class, $auth);
    $files = new Filesystem;
    $artifacts = __DIR__.'/artifacts/error-pages';
    $files->ensureDirectoryExists($artifacts);
    $compiler = new BladeCompiler($files, $artifacts);
    $engines = new EngineResolver;
    $engines->register('blade', fn () => new CompilerEngine($compiler, $files));
    $finder = new FileViewFinder($files, [dirname(__DIR__).'/resources/views']);
    $finder->addNamespace('errors', dirname(__DIR__).'/resources/views/errors');
    $factory = new Factory($engines, $finder, new Dispatcher($container));
    $factory->setContainer($container);
    $container->instance('view', $factory);
    $routes = new RouteCollection;
    $routes->add((new Route('GET', 'login', fn () => null))->name('login'));
    $routes->add((new Route('GET', 'dashboard', fn () => null))->name('dashboard'));
    $container->instance('url', new UrlGenerator($routes, Request::create('http://localhost')));
    $container->alias('url', Illuminate\Contracts\Routing\UrlGenerator::class);

    try {
        $html = $factory->make('errors.'.$status, ['exception' => new RuntimeException('DETALLE_PRIVADO')])->render();
        $destination = $authenticated ? 'http://localhost/dashboard' : 'http://localhost';
        expect($html)->toContain('lang="es"', 'id="canvas"', 'Error '.$status, 'href="'.$destination.'"', 'Volver al inicio')
            ->not->toContain('DETALLE_PRIVADO', '@yield', '{{', 'http-equiv="refresh"');
        $files->put($artifacts.'/'.$status.($authenticated ? '-authenticated' : '').'.html', $html);
    } finally {
        Container::setInstance(null);
    }
})->with([401, 402, 403, 404, 419, 429, 500, 503])->with([false, true]);

test('las direcciones inexistentes usan la sesión web sin sustituir rutas existentes', function (): void {
    $container = new Container;
    Container::setInstance($container);
    $container->instance('config', new Repository(['jetstream' => ['auth_session' => 'auth.session']]));
    $container->bind('db', fn () => throw new LogicException('Acceso a base de datos prohibido.'));
    $router = new Router(new Dispatcher($container), $container);
    Facade::setFacadeApplication($container);
    Illuminate\Support\Facades\Route::swap($router);

    try {
        $router->middleware('web')->group(dirname(__DIR__).'/routes/web.php');
        $fallback = $router->getRoutes()->match(Request::create('http://localhost/pagina-inexistente'));
        expect($fallback->isFallback)->toBeTrue()
            ->and($fallback->gatherMiddleware())->toContain('web')
            ->and($fallback->gatherMiddleware())->not->toContain('auth:sanctum');
        expect(fn () => ($fallback->getAction('uses'))())->toThrow(NotFoundHttpException::class);

        foreach (['/dashboard' => 'dashboard', '/login' => 'login', '/cartasresponsivas' => 'cartasresponsivas.index'] as $path => $name) {
            expect($router->getRoutes()->match(Request::create('http://localhost'.$path))->getName())->toBe($name);
        }
    } finally {
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication(null);
        Container::setInstance(null);
    }
});
