<?php

use App\Livewire\Admin\Servicios\Create;
use App\Livewire\Admin\Servicios\Index;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

test('componentes livewire admin servicios se pueden renderizar', function () {
    $user = User::factory()->create([
        'curp' => 'TEST123456TEST10',
        'tipo' => 1,
    ]);

    $this->actingAs($user);

    Livewire::test(Index::class)
        ->assertStatus(200);

    Livewire::test(Create::class)
        ->assertStatus(200);
});

test('genera automáticamente el ID del servicio al abrir el formulario', function () {
    $user = User::factory()->create([
        'curp' => 'TEST123456TEST10B',
        'tipo' => 1,
    ]);

    $this->actingAs($user);

    $component = Livewire::test(Create::class);

    expect($component->get('id_s'))
        ->toMatch('/^\d{4}-\d{2}-\d{2}\/\d{2}:\d{2}:\d{2}$/');
});

test('ruta servicios index carga la vista admin.servicios.index', function () {
    $user = User::factory()->create([
        'curp' => 'TEST123456TEST11',
        'tipo' => 1,
    ]);

    $response = $this->actingAs($user)->get(route('servicios.index'));

    $response->assertStatus(200);
    $response->assertViewIs('admin.servicios.index');
    $response->assertSeeLivewire(Index::class);
});

test('ruta servicios create carga la vista admin.servicios.create', function () {
    $user = User::factory()->create([
        'curp' => 'TEST123456TEST12',
        'tipo' => 1,
    ]);

    $response = $this->actingAs($user)->get(route('servicios.create'));

    $response->assertStatus(200);
    $response->assertViewIs('admin.servicios.create');
    $response->assertSeeLivewire(Create::class);
});

test('exporta los servicios filtrados con los nombres visibles en la tabla', function () {
    $admin = User::factory()->create([
        'curp' => 'TEST123456TEST901A',
        'tipo' => 1,
    ]);
    $solicitante = User::factory()->create([
        'curp' => 'TEST123456TEST901B',
        'name' => 'Nombre Solicitante',
    ]);
    $efectuo = User::factory()->create([
        'curp' => 'TEST123456TEST901C',
        'name' => 'Nombre Tecnico',
    ]);
    $vobo = User::factory()->create([
        'curp' => 'TEST123456TEST901D',
        'name' => 'Nombre VoBo',
    ]);

    Service::create([
        'id' => 901,
        'id_s' => 'SER-901',
        'F_serv' => '2026-10-15',
        'solicitante_id' => $solicitante->id,
        'efectuo_id' => $efectuo->id,
        'vobo_id' => $vobo->id,
        'capturo' => $admin->id,
        'obj_sol' => 'Texto filtrado',
        'actividades' => 'Actividad realizada',
        'observaciones' => 'Observación de prueba',
        'status' => true,
    ]);
    Service::create([
        'id' => 902,
        'id_s' => 'SER-902',
        'F_serv' => '2026-10-15',
        'solicitante_id' => $solicitante->id,
        'efectuo_id' => $efectuo->id,
        'vobo_id' => $vobo->id,
        'capturo' => $admin->id,
        'obj_sol' => 'No debe exportarse',
        'status' => false,
    ]);

    $this->actingAs($admin);

    Livewire::test(Index::class)
        ->set('filterObjetivo', 'Texto filtrado')
        ->call('exportServices')
        ->assertFileDownloaded(
            null,
            "\xEF\xBB\xBF\"ID Servicio\",Servicio,\"Código QR\",\"Fecha de servicio\",Solicitante,Efectuó,VºBº,\"Objetivo de la solicitud\",\"Actividades realizadas\",Observaciones,Estado\n".
            "1,SER-901,901,15/10/2026,\"Nombre Solicitante\",\"Nombre Tecnico\",\"Nombre VoBo\",\"Texto filtrado\",\"Actividad realizada\",\"Observación de prueba\",Activo\n",
            'text/csv; charset=UTF-8',
        );
});

test('impide exportar servicios a usuarios tipo 3', function () {
    $user = User::factory()->create([
        'curp' => 'TEST123456TEST903A',
        'tipo' => 3,
    ]);

    $this->actingAs($user);

    Livewire::test(Index::class)
        ->call('exportServices')
        ->assertForbidden();
});
