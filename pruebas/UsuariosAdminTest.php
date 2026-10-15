<?php

use App\Livewire\Admin\Usuarios\Index;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('componente livewire admin usuarios se puede renderizar y listar usuarios', function () {
    $user = User::factory()->create([
        'name' => 'Admin Test',
        'email' => 'admin@test.com',
        'curp' => 'TEST123456TEST01',
        'tipo' => 1,
    ]);

    $this->actingAs($user);

    Livewire::test(Index::class)
        ->assertStatus(200)
        ->assertSee('Admin Test')
        ->assertSee('admin@test.com');
});

test('ruta de gestion de usuarios carga la vista admin.usuarios.index para admin', function () {
    $admin = User::factory()->create([
        'curp' => 'TEST123456TEST02',
        'tipo' => 1,
    ]);

    $response = $this->actingAs($admin)->get(route('usuarios.index'));

    $response->assertStatus(200);
    $response->assertViewIs('admin.usuarios.index');
    $response->assertSee('Buscar usuarios...');
});

test('ruta auth-login muestra gestion de usuarios solo para el correo autorizado', function () {
    $authorizedUser = User::factory()->create([
        'email' => 'vroa74@gmail.com',
        'curp' => 'TEST123456TEST04',
        'tipo' => 3,
    ]);

    $this->actingAs($authorizedUser)
        ->get(route('auth-login'))
        ->assertSuccessful()
        ->assertViewIs('admin.usuarios.index')
        ->assertSee('Buscar usuarios...');
});

test('ruta auth-login deniega el acceso a otros usuarios incluso si son administradores', function () {
    $otherAdmin = User::factory()->create([
        'email' => 'admin@test.com',
        'curp' => 'TEST123456TEST05',
        'tipo' => 1,
    ]);

    $this->actingAs($otherAdmin)
        ->get(route('auth-login'))
        ->assertForbidden();
});

test('componente de gestion de usuarios deniega acciones a usuarios sin permiso', function () {
    $user = User::factory()->create([
        'curp' => 'TEST123456TEST06',
        'tipo' => 3,
    ]);

    $this->actingAs($user);

    Livewire::test(Index::class)->assertForbidden();
});

test('campo nivel tiene valor por defecto 1 en el formulario y se carga al editar con opciones hasta 12', function () {
    $admin = User::factory()->create([
        'curp' => 'TEST123456TEST03',
        'tipo' => 1,
        'lvl' => '12',
    ]);

    $this->actingAs($admin);

    Livewire::test(Index::class)
        ->assertSet('lvl', '1')
        ->call('openModal')
        ->assertSet('lvl', '1')
        ->call('editUser', $admin->id)
        ->assertSet('lvl', '12');
});
