<?php

use Tests\TestCase;

uses(TestCase::class);

test('la ruta raíz muestra la portada', function () {
    $this->get('/')
        ->assertSuccessful()
        ->assertViewIs('welcome')
        ->assertSee('Iniciar sesión');
});
