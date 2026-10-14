<?php

use App\Livewire\Profile\UpdateProfileInformationForm;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('la página de perfil muestra los campos de usuario y la opción de foto', function () {
    $user = User::factory()->create([
        'curp' => 'TESTCURP000000001',
    ]);

    $this->actingAs($user)
        ->get(route('profile.show'))
        ->assertSuccessful()
        ->assertSee('Seleccionar Nueva Foto')
        ->assertSee('RFC')
        ->assertSee('CURP')
        ->assertSee('Dirección')
        ->assertSee('Puesto')
        ->assertSee('Sexo')
        ->assertSee('Nivel')
        ->assertSee('Tipo de Usuario')
        ->assertSee('Usuario Activo');
});

test('la página de perfil permite revelar y ocultar sus tres campos de contraseña', function () {
    $user = User::factory()->create([
        'curp' => 'TESTCURP000000004',
    ]);

    $response = $this->actingAs($user)->get(route('profile.show'));

    $response->assertSuccessful()
        ->assertSee('current_password')
        ->assertSee('password_confirmation')
        ->assertSee('x-bind:type="visible ? \'text\' : \'password\'"', false)
        ->assertSee('visible = ! visible', false)
        ->assertSee('Mostrar contraseña')
        ->assertSee('Ocultar contraseña');

    expect(substr_count($response->getContent(), 'x-on:click="visible = ! visible"'))->toBe(3);
});

test('el usuario puede actualizar sus datos de perfil sin modificar su tipo ni su estado', function () {
    $user = User::factory()->create([
        'curp' => 'TESTCURP000000002',
        'tipo' => 3,
        'status' => false,
    ]);

    $this->actingAs($user);

    Livewire::test(UpdateProfileInformationForm::class)
        ->set('state', [
            'name' => 'Nombre Actualizado',
            'email' => $user->email,
            'rfc' => 'ROAV740927ABC',
            'curp' => 'ROAV740927HDFMRR01',
            'direction' => 'Dirección de Informática',
            'position' => 'Jefe de Departamento',
            'sex' => 'masculino',
            'lvl' => '7',
            'tipo' => 1,
            'status' => true,
        ])
        ->call('updateProfileInformation')
        ->assertDispatched('saved')
        ->assertSee('¡El perfil se guardó con éxito!');

    expect($user->fresh())
        ->name->toBe('Nombre Actualizado')
        ->rfc->toBe('ROAV740927ABC')
        ->curp->toBe('ROAV740927HDFMRR01')
        ->direction->toBe('Dirección de Informática')
        ->position->toBe('Jefe de Departamento')
        ->sex->toBe('masculino')
        ->lvl->toBe('7')
        ->tipo->toBe(3)
        ->status->toBeFalse();
});

test('el usuario puede guardar una foto de perfil', function () {
    Storage::fake('public');
    $user = User::factory()->create([
        'curp' => 'TESTCURP000000003',
    ]);

    $this->actingAs($user);

    Livewire::test(UpdateProfileInformationForm::class)
        ->set('photo', UploadedFile::fake()->image('avatar.png'))
        ->call('updateProfileInformation');

    $photoPath = $user->fresh()->profile_photo_path;

    expect($photoPath)->not->toBeNull();
    Storage::disk('public')->assertExists($photoPath);
    expect($user->fresh()->profile_photo_url)->toBe(url('/storage/'.$photoPath));
});

test('la url de la foto usa el dominio actual y oculta fotos que no existen', function () {
    Storage::fake('public');

    $user = User::factory()->create([
        'curp' => 'TESTCURP000000005',
        'profile_photo_path' => 'profile-photos/avatar.png',
    ]);
    Storage::disk('public')->put($user->profile_photo_path, 'foto');

    expect($user->hasProfilePhoto())->toBeTrue()
        ->and($user->profile_photo_url)->toBe(url('/storage/profile-photos/avatar.png'));

    Storage::disk('public')->delete($user->profile_photo_path);

    expect($user->hasProfilePhoto())->toBeFalse()
        ->and($user->profile_photo_url)->toContain('ui-avatars.com');
});
