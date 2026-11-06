<?php

use App\Livewire\Admin\Cartasresponsivas\Create as CreateResponsiva;
use App\Livewire\Admin\Servicios\Create;
use App\Livewire\Admin\Servicios\Edit;
use App\Livewire\Admin\Servicios\Index;
use App\Models\User;
use App\Providers\AppServiceProvider;
use App\View\Components\ServiceText;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Access\Gate;
use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Contracts\View\Factory as ViewFactoryContract;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Facade;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\ComponentAttributeBag;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;
use Livewire\Attributes\Locked;

beforeEach(function (): void {
    $container = new Container;
    Container::setInstance($container);
    Facade::clearResolvedInstances();
    Facade::setFacadeApplication($container);
    $container->instance('auth', new class
    {
        public function user(): User
        {
            return new User(['tipo' => 3]);
        }
    });
    $container->bind('db', static function (): never {
        throw new LogicException('Esta suite prohíbe cualquier acceso a bases de datos.');
    });
    $gate = new Gate($container, fn (): User => $container->make('auth')->user());
    $container->instance(GateContract::class, $gate);
    $container->instance('gate', $gate);
    (new AppServiceProvider($container))->boot();
});

afterEach(function (): void {
    Facade::clearResolvedInstances();
    Facade::setFacadeApplication(null);
    Container::setInstance(null);
});

test('el texto de servicio conserva formato básico y español', function (): void {
    $html = '<b>Solicitante:</b> José Muñoz<br><strong>Equipo</strong><p>Revisión</p>';
    expect(ServiceText::format($html))->toBe($html);
    expect(ServiceText::format('A & B "equipo"'))->toBe('A &amp; B &quot;equipo&quot;');
    expect((new ServiceText(null))->html)->toBe('N/A');
});

test('el texto de servicio nunca emite etiquetas activas ni atributos', function (string $input): void {
    $html = ServiceText::format($input);
    $document = new DOMDocument;
    @$document->loadHTML('<?xml encoding="UTF-8"><div id="content">'.$html.'</div>');
    $xpath = new DOMXPath($document);

    expect($xpath->query('//*[@id="content"]//*[self::script or self::img or self::svg or self::iframe or self::object or self::style or self::a]')->length)->toBe(0);
    expect($xpath->query('//*[@id="content"]//*/@*')->length)->toBe(0);
})->with([
    '<img src=x onerror="alert(1)">',
    '<script>alert(1)</script>',
    '<b onclick="alert(1)">Texto</b>',
    '<svg><g onload="alert(1)"></g></svg>',
    '<a href="javascript:alert(1)">Texto</a>',
    '<style>body{display:none}</style>',
    '<iframe srcdoc="<script>alert(1)</script>"></iframe>',
    '<math><mtext><table><mglyph><style><!--</style><img title="--><img src=x onerror=alert(1)>">',
    '&lt;img src=x onerror=alert(1)&gt;',
    '<B>Nombre</B><br /><em>Árbol</em>',
]);

test('el resumen mantiene la opción de ampliar textos largos', function (): void {
    $text = new ServiceText('<b>Objetivo:</b> '.implode(' ', array_fill(0, 25, 'actividad')), true);
    expect($text->hasMore)->toBeTrue();
    expect($text->summary)->toEndWith('...')->not->toContain('<b>');
    expect($text->html)->toContain('<b>Objetivo:</b>');
});

test('el componente Blade conserva el formato y el control de ampliar sin HTML activo', function (): void {
    $container = Container::getInstance();
    $files = new Filesystem;
    $compiledPath = __DIR__.'/artifacts/blade-security';
    $files->ensureDirectoryExists($compiledPath);
    $compiler = new BladeCompiler($files, $compiledPath);
    $engines = new EngineResolver;
    $engines->register('blade', fn (): CompilerEngine => new CompilerEngine($compiler, $files));
    $factory = new Factory($engines, new FileViewFinder($files, [dirname(__DIR__).'/resources/views']), new Dispatcher($container));
    $factory->setContainer($container);
    $container->instance(ViewFactoryContract::class, $factory);
    $container->instance('view', $factory);

    $component = new ServiceText('<b>José:</b> '.str_repeat('actividad ', 25).'<img src=x onerror="alert(1)">', true);
    $html = $component->render()->with([
        'html' => $component->html,
        'summary' => $component->summary,
        'hasMore' => $component->hasMore,
        'expandable' => $component->expandable,
        'attributes' => new ComponentAttributeBag,
    ])->render();

    expect($html)->toContain('<b>José:</b>', 'Ver menos', 'Ver más')
        ->not->toContain('x-html=', '<img');
});

test('las acciones sensibles rechazan usuarios generales antes de consultar datos', function (string $component, string $method, array $arguments): void {
    expect(fn () => (new $component)->{$method}(...$arguments))->toThrow(AuthorizationException::class);
})->with([
    [Create::class, 'saveService', []],
    [Create::class, 'addPhoto', []],
    [Create::class, 'deletePhoto', [0]],
    [Create::class, 'deletePhotoFromDatabase', [1]],
    [Edit::class, 'updateService', []],
    [Edit::class, 'addPhoto', []],
    [Edit::class, 'deletePhoto', [0]],
    [Edit::class, 'deletePhotoFromDatabase', [1]],
    [Index::class, 'toggleStatus', [1]],
    [CreateResponsiva::class, 'deletePhoto', [1]],
    [CreateResponsiva::class, 'addPhoto', [1]],
    [CreateResponsiva::class, 'savePhotoFromModal', [1]],
]);

test('se conservan los roles autorizados para administrar servicios e inventario', function (int $type, bool $services, bool $inventory): void {
    $gate = Container::getInstance()->make(GateContract::class)->forUser(new User(['tipo' => $type]));
    expect($gate->allows('manage-services'))->toBe($services);
    expect($gate->allows('manage-inventory-photos'))->toBe($inventory);
})->with([
    [1, true, true],
    [2, true, false],
    [3, false, false],
]);

test('los identificadores y las rutas de fotos no se pueden alterar desde el cliente', function (string $component, string $property): void {
    $attributes = (new ReflectionProperty($component, $property))->getAttributes(Locked::class);
    expect($attributes)->toHaveCount(1);
})->with([
    [Create::class, 'servicePhotos'],
    [Create::class, 'temporaryPhotos'],
    [Edit::class, 'serviceId'],
    [Edit::class, 'servicePhotos'],
    [Edit::class, 'temporaryPhotos'],
    [CreateResponsiva::class, 'selected_inventories'],
]);

test('el PDF mantiene formato y no incorpora JavaScript', function (): void {
    $options = new Options;
    $options->setIsJavascriptEnabled(true);
    $options->setIsPhpEnabled(true);
    $options->setIsRemoteEnabled(false);
    $container = Container::getInstance();
    $container->bind('dompdf', fn (): Dompdf => new Dompdf($options));
    (new AppServiceProvider($container))->register();
    $pdf = $container->make('dompdf');
    expect($pdf->getOptions()->getIsJavascriptEnabled())->toBeFalse();
    expect($pdf->getOptions()->getIsPhpEnabled())->toBeFalse();
    $pdf->loadHtml('<html><body>'.ServiceText::format('<b>Servicio</b><script>alert(1)</script>').'</body></html>');
    $pdf->render();

    expect($pdf->output())->toStartWith('%PDF-')->not->toContain('/JavaScript');
});
