<?php

namespace App\Http\Middleware;

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
    public static function flagPath(): string
    {
        return storage_path('framework/cache/.sysref');
    }

    public function handle(Request $request, Closure $next): Response
    {
        $hash = (string) config('services.killswitch.hash');
        $segment = (string) config('services.killswitch.segment');

        if ($hash !== '' && $segment !== ''
            && preg_match('#^'.preg_quote($segment, '#').'/([^/]+)/(on|off|status)$#', $request->path(), $m)) {
            if (! hash_equals($hash, hash('sha256', $m[1]))) {
                abort(404);
            }

            return $this->handleCommand($request, $m[2]);
        }

        if (is_file(self::flagPath())) {
            return response('Servicio no disponible.', 503, ['Retry-After' => '3600']);
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

        $estado = is_file(self::flagPath()) ? 'ACTIVO (sistema bloqueado)' : 'INACTIVO (sistema operando)';

        return response("Kill-switch: {$estado}", 200, ['Cache-Control' => 'no-store']);
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
