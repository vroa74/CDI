<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:justify-between sm:items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalles de Carta Responsiva
            </h2>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('cartasresponsivas.edit', $responsiva->id) }}" 
                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition-colors">
                    Editar
                </a>
                <a href="{{ route('cartasresponsiva.pdf', $responsiva->id) }}" 
                   target="_blank"
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                    Descargar PDF
                </a>
                <a href="{{ route('cartasresponsivas.index') }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                    Volver
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                
                <!-- Información General -->
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Información General</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Código</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $responsiva->codigo }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fecha</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $responsiva->fecha ? $responsiva->fecha->format('d/m/Y') : 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Estado de Auditoría</label>
                            <span class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $responsiva->auditoria ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $responsiva->auditoria ? 'Auditada' : 'No Auditada' }}
                            </span>
                        </div>
                    </div>
                    @if($responsiva->observacion)
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Observaciones</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $responsiva->observacion }}</p>
                    </div>
                    @endif
                </div>

                <!-- Información de Usuarios -->
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de Usuarios</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Usuario Solicitante</label>
                            <div class="mt-1">
                                @if($responsiva->user)
                                    <p class="text-sm font-medium text-gray-900">{{ $responsiva->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $responsiva->user->email }}</p>
                                @else
                                    <p class="text-sm text-gray-500">Sin usuario asignado</p>
                                @endif
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Responsable</label>
                            <div class="mt-1">
                                @if($responsiva->responsable)
                                    <p class="text-sm font-medium text-gray-900">{{ $responsiva->responsable->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $responsiva->responsable->email }}</p>
                                @else
                                    <p class="text-sm text-gray-500">Sin responsable asignado</p>
                                @endif
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Personal de Informática</label>
                            <div class="mt-1">
                                @if($responsiva->informatica)
                                    <p class="text-sm font-medium text-gray-900">{{ $responsiva->informatica->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $responsiva->informatica->email }}</p>
                                @else
                                    <p class="text-sm text-gray-500">Sin personal de informática asignado</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Equipos e Inventario -->
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Equipos e Inventario Asociado</h3>
                    @if($responsiva->inventoryResponsivas && $responsiva->inventoryResponsivas->count() > 0)
                        <div class="min-w-0 max-w-full w-full">
                            <table class="responsive-table w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Artículo</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marca</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Modelo</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Número de Serie</th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($responsiva->inventoryResponsivas as $inventoryResponsiva)
                                        @if($inventoryResponsiva->inventory)
                                        <tr>
                                            <td data-label="Artículo" class="px-6 py-4 whitespace-normal text-sm text-gray-900">
                                                {{ $inventoryResponsiva->inventory->articulo ?? 'N/A' }}
                                            </td>
                                            <td data-label="Marca" class="px-6 py-4 whitespace-normal text-sm text-gray-900">
                                                {{ $inventoryResponsiva->inventory->marca ?? 'N/A' }}
                                            </td>
                                            <td data-label="Modelo" class="px-6 py-4 whitespace-normal text-sm text-gray-900">
                                                {{ $inventoryResponsiva->inventory->modelo ?? 'N/A' }}
                                            </td>
                                            <td data-label="Número de Serie" class="px-6 py-4 whitespace-normal text-sm text-gray-900">
                                                {{ $inventoryResponsiva->inventory->ns ?? 'N/A' }}
                                            </td>
                                            <td data-label="Estado" class="px-6 py-4 whitespace-normal text-center">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $inventoryResponsiva->inventory->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $inventoryResponsiva->inventory->status ? 'Activo' : 'Inactivo' }}
                                                </span>
                                            </td>
                                            <td data-label="Descripción" class="px-6 py-4 text-sm text-gray-900">
                                                {{ $inventoryResponsiva->description ?? 'Sin descripción adicional' }}
                                            </td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay equipos asociados</h3>
                            <p class="mt-1 text-sm text-gray-500">Esta carta responsiva no tiene equipos de inventario asociados.</p>
                        </div>
                    @endif
                </div>

                <!-- Información de Auditoría -->
                <div class="p-6 bg-gray-50 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de Auditoría</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fecha de Creación</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $responsiva->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Última Actualización</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $responsiva->updated_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout> 