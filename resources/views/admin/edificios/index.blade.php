<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-100 leading-tight flex items-center gap-2">
            <i class="ri-building-2-line"></i>
            {{ __('Edificios') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if (session('message'))
                <div class="mb-4 rounded-lg border border-green-700 bg-green-900/40 px-4 py-3 text-green-200">{{ session('message') }}</div>
            @endif
            @if (session('error'))
                <div class="mb-4 rounded-lg border border-red-700 bg-red-900/40 px-4 py-3 text-red-200">{{ session('error') }}</div>
            @endif

            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white">Edificios</h2>
                    <p class="text-sm text-gray-400">Administra los edificios disponibles para la red.</p>
                </div>
                <a href="{{ route('edificios.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700">
                    <x-lucide name="plus" class="h-4 w-4" />
                    Nuevo edificio
                </a>
            </div>

            <div class="rounded-2xl border border-gray-700 bg-gray-800 p-5 shadow-xl">
                <form method="GET" action="{{ route('edificios.index') }}">
                    <input name="search" value="{{ request('search') }}" type="search" placeholder="Buscar por nombre o dirección..." class="w-full rounded-lg border-gray-700 bg-gray-900 text-white placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500">
                </form>

                <div class="mt-5 min-w-0 max-w-full w-full">
                    <table class="responsive-table w-full divide-y divide-gray-700">
                        <thead><tr class="text-left text-xs uppercase tracking-wider text-gray-400"><th class="px-4 py-3">Nombre</th><th class="px-4 py-3">Dirección</th><th class="px-4 py-3 text-right">Acciones</th></tr></thead>
                        <tbody class="divide-y divide-gray-700 text-sm text-gray-200">
                            @forelse ($edificios as $edificio)
                                <tr class="hover:bg-gray-700/40">
                                    <td data-label="Nombre" class="px-4 py-3 font-medium text-white">{{ $edificio->nombre }}</td>
                                    <td data-label="Dirección" class="px-4 py-3">{{ $edificio->direccion ?: 'Sin dirección' }}</td>
                                    <td data-label="Acciones" class="px-4 py-3 text-right">
                                        <a href="{{ route('edificios.edit', $edificio) }}" class="mr-3 text-blue-400 hover:text-blue-300" title="Editar"><x-lucide name="pencil" class="inline h-4 w-4" /></a>
                                        <form method="POST" action="{{ route('edificios.destroy', $edificio) }}" class="inline" onsubmit="return confirm('¿Deseas eliminar este edificio?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-300" title="Eliminar"><x-lucide name="trash-2" class="inline h-4 w-4" /></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-4 py-8 text-center text-gray-400">No hay edificios registrados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $edificios->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>