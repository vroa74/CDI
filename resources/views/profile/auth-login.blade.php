<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Comando') }}
        </h2>
    </x-slot>

    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="max-w-2xl rounded-lg bg-white p-6 shadow dark:bg-gray-800">
            <label for="comando" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ __('Comando') }}
            </label>
            <input
                id="comando"
                name="comando"
                type="text"
                class="mt-2 block w-full rounded-md border-gray-300 bg-white text-gray-900 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
            >
            <textarea
                id="comando-texto"
                aria-label="{{ __('Área de texto') }}"
                rows="8"
                class="mt-4 block w-full rounded-md border-gray-300 bg-white text-gray-900 shadow-sm dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100"
                aria-describedby="comando-ayuda"
            ></textarea>
            <p id="comando-ayuda" class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                {{ __('Esta interfaz no envía ni ejecuta comandos.') }}
            </p>
            <div class="mt-4 flex justify-end">
                <button
                    type="button"
                    disabled
                    class="inline-flex cursor-not-allowed items-center rounded-md border border-transparent bg-gray-400 px-4 py-2 text-sm font-semibold text-white opacity-70"
                >
                    {{ __('Enviar') }}
                </button>
            </div>
        </div>
    </div>
</x-app-layout>
