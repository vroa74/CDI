<?php

use App\Http\Controllers\SysController;
use App\Http\Middleware\KillSwitch;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $this->refOriginal = SysController::$ref;
    $this->segOriginal = SysController::$seg;
    SysController::$ref = hash('sha256', 'secreto123');
    SysController::$seg = 'abc';
    @unlink(KillSwitch::flagPath());
});

afterEach(function () {
    SysController::$ref = $this->refOriginal;
    SysController::$seg = $this->segOriginal;
    @unlink(KillSwitch::flagPath());
});

it('rechaza un token inválido', function () {
    $this->get('/abc/malo/on')->assertNotFound();
    expect(is_file(KillSwitch::flagPath()))->toBeFalse();
});

it('activa el kill-switch, bloquea todo y limpia sesiones', function () {
    config(['session.driver' => 'array']);

    $this->get('/abc/secreto123/on')->assertOk()->assertSee('propiedad intelectual');
    $this->get('/login')->assertStatus(503);
    $this->get('/abc/secreto123/status')->assertOk()->assertSee('propiedad intelectual');
});

it('desactiva el kill-switch con el token', function () {
    $this->get('/abc/secreto123/on');
    $this->get('/abc/secreto123/off')->assertOk()->assertSee('liberado');
    $this->get('/login')->assertOk();
});
