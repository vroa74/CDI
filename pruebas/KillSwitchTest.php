<?php

use App\Http\Middleware\KillSwitch;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    config(['services.killswitch.hash' => hash('sha256', 'secreto123'), 'services.killswitch.segment' => 'abc']);
    @unlink(KillSwitch::flagPath());
});

afterEach(fn () => @unlink(KillSwitch::flagPath()));

it('rechaza un token inválido', function () {
    $this->get('/abc/malo/on')->assertNotFound();
    expect(is_file(KillSwitch::flagPath()))->toBeFalse();
});

it('activa el kill-switch, bloquea todo y limpia sesiones', function () {
    config(['session.driver' => 'array']);

    $this->get('/abc/secreto123/on')->assertOk()->assertSee('ACTIVO');
    $this->get('/login')->assertStatus(503);
    $this->get('/abc/secreto123/status')->assertOk()->assertSee('ACTIVO');
});

it('desactiva el kill-switch con el token', function () {
    $this->get('/abc/secreto123/on');
    $this->get('/abc/secreto123/off')->assertOk()->assertSee('INACTIVO');
    $this->get('/login')->assertOk();
});
