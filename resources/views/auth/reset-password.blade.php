<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="block">
                <x-label for="email" value="{{ __('Correo electrónico') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Nueva contraseña') }}" />
                <div class="relative mt-1" x-data="{ show: false }">
                    <input id="password" class="block w-full rounded-md border-gray-600 bg-gray-900 p-2.5 pr-10 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" x-bind:type="show ? 'text' : 'password'" name="password" required autocomplete="new-password" />
                    <x-password-toggle-button />
                </div>
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirmar contraseña') }}" />
                <div class="relative mt-1" x-data="{ show: false }">
                    <input id="password_confirmation" class="block w-full rounded-md border-gray-600 bg-gray-900 p-2.5 pr-10 text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" x-bind:type="show ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password" />
                    <x-password-toggle-button />
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('Restablecer contraseña') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
