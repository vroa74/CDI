<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CartasCesponsivasController;
use App\Http\Controllers\CartasResponsivaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EdificioController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\LineaInternetController;
use App\Http\Controllers\ReporteIncidenteController;
use App\Http\Controllers\SeguimientoSubreporteController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UsuariosController;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;

Route::get('/', function () {
    return view('welcome');
});

// ============================================================================
// RUTAS DE AUTENTICACIÓN PERSONALIZADAS
// ============================================================================

// Rutas de Login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

// Rutas de Registro (DESHABILITADAS)
// Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
// Route::post('/register', [RegisterController::class, 'register']);

// ============================================================================
// RUTAS PROTEGIDAS (REQUIEREN AUTENTICACIÓN)
// ============================================================================

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {

    // =========================================================================
    // REPORTES PDF
    // =========================================================================

    Route::middleware('can:view-reports')->group(function () {
        Route::get('/service-pdf/{id}', [ServiceController::class, 'generatePdf'])->name('service.pdf');
        Route::get('/service-cal-pdf/{id}', [ServiceController::class, 'generatePdfCal'])->name('service.pdf.cal');
        Route::get('/service-details-pdf/{id}', [ServiceController::class, 'generateDetailsPdf'])->name('service.details.pdf');
        Route::get('/inventory-pdf/{id}', [InventoryController::class, 'generatePdf'])->name('inventory.pdf');
    });

    // ========================================================================
    // DASHBOARD
    // ========================================================================

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ========================================================================
    // GESTIÓN DE USUARIOS
    // ========================================================================

    Route::get('/auth-login', [UsuariosController::class, 'authLogin'])
        ->middleware('can:access-auth-login')
        ->name('auth-login');

    Route::resource('usuarios', UsuariosController::class)->names('usuarios');

    // Componente Livewire de usuarios
    Route::get('/usuarios-livewire', function () {
        return view('admin.usuarios.livewire');
    })->name('usuarios.livewire');

    // ========================================================================
    // GESTIÓN DE SERVICIOS
    // ========================================================================

    Route::resource('servicios', ServiceController::class)->names('servicios');

    // Componente Livewire de servicios
    Route::get('/servicios-livewire', function () {
        return view('admin.servicios.index');
    })->name('servicios.livewire');

    // ========================================================================
    // GESTIÓN DE INVENTARIO
    // ========================================================================

    Route::resource('inventario', InventoryController::class)->names('inventario');
    Route::get('/inventory-user-inv', [InventoryController::class, 'userinv'])->name('inventory.user-inv');
    Route::get('/inventory-responsables', [InventoryController::class, 'responsables'])->name('inventory.responsables');
    Route::get('/inventario/export/csv', [InventoryController::class, 'exportCSV'])->name('inventario.export.csv');
    Route::get('/inventario/export/html', [InventoryController::class, 'exportHTML'])->name('inventario.export.html');

    // ========================================================================
    // GESTIÓN DE CARTAS RESPONSIVAS
    // ========================================================================

    // Rutas principales de cartas responsivas
    Route::resource('cartasresponsivas', CartasCesponsivasController::class)->names('cartasresponsivas');

    // Rutas para la página de carta responsiva
    Route::get('/cartasresponsiva/{id}', [CartasResponsivaController::class, 'show'])->name('cartasresponsiva.show');
    Route::get('/cartasresponsiva-codigo/{codigo}', [CartasResponsivaController::class, 'showByCode'])->name('cartasresponsiva.showByCode');

    // Ruta para generar PDF
    Route::get('/cartasresponsiva-pdf/{id}', [CartasResponsivaController::class, 'generatePdf'])->name('cartasresponsiva.pdf');

    // ========================================================================
    // REPORTES DE RED Y REPORTES GENERALES
    // ========================================================================

    Route::resource('edificios', EdificioController::class)
        ->except(['show'])
        ->names('edificios');
    Route::resource('lineas-internet', LineaInternetController::class)
        ->except(['show'])
        ->names('lineas-internet');
    Route::resource('reportes-red', ReporteIncidenteController::class)
        ->parameters(['reportes-red' => 'reporte'])
        ->names('reportes-incidentes');
    Route::prefix('reportes-red/{reporte}/seguimientos')->name('reportes-incidentes.seguimientos.')->group(function () {
        Route::get('create', [SeguimientoSubreporteController::class, 'create'])->name('create');
        Route::post('/', [SeguimientoSubreporteController::class, 'store'])->name('store');
        Route::get('{seguimiento}/edit', [SeguimientoSubreporteController::class, 'edit'])->name('edit');
        Route::put('{seguimiento}', [SeguimientoSubreporteController::class, 'update'])->name('update');
        Route::delete('{seguimiento}', [SeguimientoSubreporteController::class, 'destroy'])->name('destroy');
    });

    // Módulo Reportes Segmentado
    Route::get('/reportes', function () {
        return view('admin.reportes.index');
    })->name('admin.reportes.index');

    Route::get('/reportes/create', function () {
        return view('admin.reportes.create');
    })->name('admin.reportes.create');

    Route::get('/reportes/edit/{id?}', function ($id = null) {
        return view('admin.reportes.edit', ['id' => $id]);
    })->name('admin.reportes.edit');

    // ========================================================================
    // CATÁLOGO / MATERIAS
    // ========================================================================

    Route::get('/catalogo', function () {
        return view('admin.catalogo.index');
    })->name('admin.catalogo.index');

    Route::get('/materias', function () {
        return view('admin.catalogo.index');
    })->name('materias');

    // ========================================================================
    // ESTUDIANTES
    // ========================================================================

    Route::get('/estudiante', function () {
        return view('admin.estudiante.index');
    })->name('admin.estudiante.index');

    // ========================================================================
    // USUARIO (PERFIL / VISTA SEGMENTADA)
    // ========================================================================

    Route::get('/usuario', function () {
        return view('admin.usuario.index');
    })->name('admin.usuario.index');
});

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {

//     Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
//     // Route::get('/dashboard', function () {    return view('dashboard');   })->name('dashboard');

// });
