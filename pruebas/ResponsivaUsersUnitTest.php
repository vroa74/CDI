<?php

use App\Livewire\Admin\Cartasresponsivas\Create;
use App\Models\User;

test('el generador admite usuarios de cualquier dirección y estado', function (): void {
    foreach ([true, false] as $status) {
        expect(Create::userMatchesTarget(new User(['direction' => '', 'status' => $status]), 'user_id'))->toBeTrue();
    }
});

test('los cargos corresponden únicamente a su dirección', function (string $direction, string $target, bool $allowed): void {
    expect(Create::userMatchesTarget(new User(['direction' => $direction]), $target))->toBe($allowed);
})->with([
    ['ÓRGANO INTERNO DE CONTROL', 'responsable_id', true],
    ['  órgano   interno de control  ', 'responsable_id', true],
    ['DIRECCIÓN DE INFORMÁTICA', 'informatica_id', true],
    ['direccion de informatica', 'informatica_id', true],
    ['DIRECCIÓN DE INFORMÁTICA', 'responsable_id', false],
    ['ÓRGANO INTERNO DE CONTROL', 'informatica_id', false],
    ['OTRA DIRECCIÓN', 'responsable_id', false],
    ['OTRA DIRECCIÓN', 'informatica_id', false],
    ['', 'informatica_id', false],
    ['ÓRGANO INTERNO DE CONTROL', 'otro_campo', false],
]);

test('los selectores y la búsqueda conservan el filtro de dirección', function (): void {
    $component = new Create;
    $component->users = collect([
        (new User)->forceFill(['id' => 1, 'name' => 'Ana', 'email' => 'ana@example.test', 'direction' => 'OTRA', 'status' => true]),
        (new User)->forceFill(['id' => 2, 'name' => 'Ana Control', 'email' => 'control@example.test', 'direction' => 'ÓRGANO INTERNO DE CONTROL', 'status' => false]),
        (new User)->forceFill(['id' => 3, 'name' => 'Ana Informática', 'email' => 'it@example.test', 'direction' => 'DIRECCIÓN DE INFORMÁTICA', 'status' => false]),
    ]);

    expect($component->usersForTarget('user_id')->pluck('id')->all())->toBe([1, 2, 3])
        ->and($component->usersForTarget('responsable_id')->pluck('id')->all())->toBe([2])
        ->and($component->usersForTarget('informatica_id')->pluck('id')->all())->toBe([3]);

    $component->userModalTarget = 'responsable_id';
    $component->userModalFilter = 'ana';
    $component->updateUserModalList();
    expect($component->userModalList->pluck('id')->all())->toBe([2]);

    $component->userModalFilter = 'it@example.test';
    $component->updateUserModalList();
    expect($component->userModalList)->toHaveCount(0);
});
