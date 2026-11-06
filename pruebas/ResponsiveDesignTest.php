<?php

use App\Livewire\Admin\Servicios\Create;
use App\Livewire\Admin\Servicios\Edit;
use App\Livewire\Admin\Servicios\Index;
use App\Models\Inventory;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    expect(config('database.default'))->toBe('sqlite');
    expect(config('database.connections.sqlite.database'))->toBe(':memory:');
});

test('servicios conserva los ocho campos y las acciones en la presentación adaptable', function () {
    $admin = User::factory()->create(['tipo' => 1]);
    $this->actingAs($admin);

    $service = Service::factory()->create([
        'id_s' => '2026-11-06/12:30:00',
        'F_serv' => '2026-11-06',
        'solicitante_id' => $admin->id,
        'efectuo_id' => $admin->id,
        'vobo_id' => $admin->id,
        'capturo' => $admin->id,
        'obj_sol' => str_repeat('ObjetivoSinEspacios', 30),
        'actividades' => 'Revisión del equipo',
        'observaciones' => 'Observaciones del servicio',
        'status' => true,
    ]);

    $component = Livewire::test(Index::class)
        ->assertSee($service->id_s)
        ->assertSee('Revisión del equipo')
        ->assertSee('Observaciones del servicio')
        ->assertSee('editService('.$service->id.')', false)
        ->assertSee('generateIndividualServiceReport('.$service->id.')', false);

    $document = new DOMDocument;
    @$document->loadHTML('<?xml encoding="UTF-8">'.$component->html());
    $xpath = new DOMXPath($document);
    $table = '//table[contains(concat(" ", normalize-space(@class), " "), " responsive-table ")]';

    expect($xpath->query($table.'/tbody/tr/td[@data-label]')->length)->toBe(8);
    expect($xpath->query($table.'/ancestor::*[contains(@class, "overflow-x-auto")]')->length)->toBe(0);
    expect($xpath->query($table.'/tbody/tr[@*[name()="wire:key"]="service-'.$service->id.'"]')->length)->toBe(1);

    $component->set('filterObjetivo', 'Sin coincidencias')
        ->assertSee('No se encontraron servicios')
        ->assertDontSee($service->id_s);
});

test('servicios muestra el estado vacío en el mismo contenedor adaptable', function () {
    $this->actingAs(User::factory()->create(['tipo' => 1]));

    Livewire::test(Index::class)
        ->assertSee('responsive-table', false)
        ->assertSee('No se encontraron servicios');
});

test('los modales de servicios conservan todos los campos y permiten cerrar sin desplazamiento lateral', function (string $componentClass, string $openMethod, array $arguments, string $closeMethod, int $fields) {
    $admin = User::factory()->create([
        'tipo' => 1,
        'name' => str_repeat('NombreLargo', 15),
        'position' => str_repeat('CargoLargo', 15),
        'direction' => str_repeat('DireccionLarga', 15),
    ]);
    $this->actingAs($admin);

    $service = Service::factory()->create([
        'id_s' => '2026-11-06/13:00:00',
        'solicitante_id' => $admin->id,
        'efectuo_id' => $admin->id,
        'vobo_id' => $admin->id,
        'capturo' => $admin->id,
    ]);
    Inventory::factory()->create([
        'ni' => 'NI-PRUEBA',
        'ns' => str_repeat('SerieLarga', 20),
        'articulo' => str_repeat('ArticuloLargo', 15),
        'user_id' => $admin->id,
        'res_id' => $admin->id,
    ]);

    $component = Livewire::test($componentClass, $componentClass === Edit::class ? ['id' => $service->id] : [])
        ->call($openMethod, ...$arguments)
        ->assertStatus(200);

    $document = new DOMDocument;
    @$document->loadHTML('<?xml encoding="UTF-8">'.$component->html());
    $xpath = new DOMXPath($document);
    $modal = '//*[contains(concat(" ", normalize-space(@class), " "), " service-modal ")]';
    expect($xpath->query($modal)->length)->toBe(1);
    expect($xpath->query($modal.'//*[contains(@class, "overflow-x-auto") or contains(@class, "whitespace-nowrap")]')->length)->toBe(0);

    if ($fields > 0) {
        expect($xpath->query($modal.'//table[contains(@class, "responsive-table")]/tbody/tr/td[@data-label]')->length)->toBe($fields);
        expect($xpath->query($modal.'//td[@data-label="Acciones"]//button[contains(@class, "modal-action")] | '.$modal.'//td[@data-label="NI / Seleccionar"]//button[contains(@class, "modal-action")]')->length)->toBeGreaterThan(0);
    }

    $component->call($closeMethod)->assertDontSee('service-modal', false);
})->with([
    'crear: usuarios' => [Create::class, 'openUserModal', ['solicitante'], 'closeModal', 8],
    'editar: usuarios' => [Edit::class, 'openUserModal', ['solicitante'], 'closeModal', 8],
    'crear: inventarios' => [Create::class, 'openInventoryModal', [], 'closeInventoryModal', 5],
    'editar: inventarios' => [Edit::class, 'openInventoryModal', [], 'closeInventoryModal', 5],
    'crear: agregar inventario' => [Create::class, 'openInventorySelection', [], 'closeInventorySelection', 5],
    'editar: agregar inventario' => [Edit::class, 'openInventorySelection', [], 'closeInventorySelection', 5],
    'crear: fotos' => [Create::class, 'openPhotoForm', [], 'closePhotoForm', 0],
    'editar: fotos' => [Edit::class, 'openPhotoForm', [], 'closePhotoForm', 0],
]);
