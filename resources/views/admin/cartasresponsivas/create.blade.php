<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight">
            {{ __('Nueva carta responsiva') }}
        </h2>
    </x-slot>

    <div class="py-4">
        @livewire('admin.cartasresponsivas.create')
    </div>
</x-app-layout>
