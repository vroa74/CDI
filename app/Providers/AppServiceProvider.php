<?php

namespace App\Providers;

use App\Models\User;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->extend('dompdf', static function (Dompdf $dompdf): Dompdf {
            // Aplicar también cuando Laravel tenga configuración cacheada.
            $dompdf->getOptions()->setIsJavascriptEnabled(false);
            $dompdf->getOptions()->setIsPhpEnabled(false);

            return $dompdf;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('access-auth-login', fn (User $user): bool => $user->email === 'vroa74@gmail.com');
        Gate::define('manage-users', fn (User $user): bool => $user->tipo === 1 || $user->email === 'vroa74@gmail.com');
        Gate::define('view-reports', fn (User $user): bool => $user->tipo === 1 || $user->email === 'vroa74@gmail.com');
        Gate::define('manage-services', fn (User $user): bool => in_array((int) $user->tipo, [1, 2], true));
        Gate::define('manage-inventory-photos', fn (User $user): bool => (int) $user->tipo === 1);
    }
}
