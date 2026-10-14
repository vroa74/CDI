<x-form-section submit="updatePassword">
    <x-slot name="title">
        {{ __('Actualizar contraseña') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Asegúrate de que tu cuenta use una contraseña larga y aleatoria para mantenerla segura.') }}
    </x-slot>

    <x-slot name="form">
        <div x-data="{ visible: false }" class="col-span-6 sm:col-span-4">
            <x-label for="current_password" value="{{ __('Contraseña actual') }}" />
            <div class="relative mt-1">
                <x-input id="current_password" x-bind:type="visible ? 'text' : 'password'" class="block w-full pr-10" wire:model="state.current_password" autocomplete="current-password" />
                <button type="button" x-on:click="visible = ! visible" x-bind:aria-label="visible ? 'Ocultar contraseña' : 'Mostrar contraseña'" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-md">
                    <svg x-show="! visible" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7Z" />
                    </svg>
                    <svg x-show="visible" x-cloak class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m3 3 18 18M10.584 10.587a2 2 0 0 0 2.829 2.829" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.88 5.09A10.94 10.94 0 0 1 12 4.89c4.478 0 8.268 2.943 9.542 7a11.05 11.05 0 0 1-3.07 4.522M6.228 6.228A11.03 11.03 0 0 0 2.458 12c1.274 4.057 5.064 7 9.542 7 1.61 0 3.13-.39 4.47-1.08" />
                    </svg>
                </button>
            </div>
            <x-input-error for="current_password" class="mt-2" />
        </div>

        <div x-data="{ visible: false }" class="col-span-6 sm:col-span-4">
            <x-label for="password" value="{{ __('Nueva contraseña') }}" />
            <div class="relative mt-1">
                <x-input id="password" x-bind:type="visible ? 'text' : 'password'" class="block w-full pr-10" wire:model="state.password" autocomplete="new-password" />
                <button type="button" x-on:click="visible = ! visible" x-bind:aria-label="visible ? 'Ocultar contraseña' : 'Mostrar contraseña'" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-md">
                    <svg x-show="! visible" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7Z" />
                    </svg>
                    <svg x-show="visible" x-cloak class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m3 3 18 18M10.584 10.587a2 2 0 0 0 2.829 2.829" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.88 5.09A10.94 10.94 0 0 1 12 4.89c4.478 0 8.268 2.943 9.542 7a11.05 11.05 0 0 1-3.07 4.522M6.228 6.228A11.03 11.03 0 0 0 2.458 12c1.274 4.057 5.064 7 9.542 7 1.61 0 3.13-.39 4.47-1.08" />
                    </svg>
                </button>
            </div>
            <x-input-error for="password" class="mt-2" />
        </div>

        <div x-data="{ visible: false }" class="col-span-6 sm:col-span-4">
            <x-label for="password_confirmation" value="{{ __('Confirmar contraseña') }}" />
            <div class="relative mt-1">
                <x-input id="password_confirmation" x-bind:type="visible ? 'text' : 'password'" class="block w-full pr-10" wire:model="state.password_confirmation" autocomplete="new-password" />
                <button type="button" x-on:click="visible = ! visible" x-bind:aria-label="visible ? 'Ocultar contraseña' : 'Mostrar contraseña'" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded-md">
                    <svg x-show="! visible" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7Z" />
                    </svg>
                    <svg x-show="visible" x-cloak class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m3 3 18 18M10.584 10.587a2 2 0 0 0 2.829 2.829" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.88 5.09A10.94 10.94 0 0 1 12 4.89c4.478 0 8.268 2.943 9.542 7a11.05 11.05 0 0 1-3.07 4.522M6.228 6.228A11.03 11.03 0 0 0 2.458 12c1.274 4.057 5.064 7 9.542 7 1.61 0 3.13-.39 4.47-1.08" />
                    </svg>
                </button>
            </div>
            <x-input-error for="password_confirmation" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Guardado.') }}
        </x-action-message>

        <x-button>
            {{ __('Guardar') }}
        </x-button>
    </x-slot>
</x-form-section>
