<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Editar carta responsiva') }}
        </h2>
    </x-slot>

    <div class="py-4">
        @livewire('admin.cartasresponsivas.create', ['id' => $id])
    </div>
</x-app-layout>
