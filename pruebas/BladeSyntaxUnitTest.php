<?php

use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Symfony\Component\Process\Process;

dataset('plantillas Blade del proyecto', function (): array {
    $directory = dirname(__DIR__).'/resources/views';
    $paths = [];

    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory)) as $file) {
        if ($file->isFile() && str_ends_with($file->getFilename(), '.blade.php')) {
            $relativePath = str_replace('\\', '/', substr($file->getPathname(), strlen($directory) + 1));
            $paths[$relativePath] = [$file->getPathname()];
        }
    }

    ksort($paths);

    return $paths;
});

test('las directivas de cada plantilla Blade generan PHP válido sin iniciar Laravel', function (string $path): void {
    $files = new Filesystem;
    $compiledDirectory = __DIR__.'/artifacts/blade-syntax';
    $files->ensureDirectoryExists($compiledDirectory);
    $compiler = new BladeCompiler($files, $compiledDirectory);
    // No resolver componentes: podría iniciar la aplicación y sus proveedores.
    $compiler->withoutComponentTags();
    $source = $files->get($path);
    $compiledPath = $compiledDirectory.'/'.hash('sha256', $path).'.php';
    $files->put($compiledPath, $compiler->compileString($source));

    $process = new Process([PHP_BINARY, '-n', '-l', $compiledPath]);
    $process->run();

    expect($process->isSuccessful(), $path."\n".$process->getOutput().$process->getErrorOutput())->toBeTrue();
    expect(preg_match('/data-label="[^"\r\n]*@(if|else|endif|foreach|endforeach)\b/', $source), $path)->toBe(0);
})->with('plantillas Blade del proyecto');
