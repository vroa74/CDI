<div class="min-h-screen bg-gray-900 p-4">
    <!-- Header con título y botón de crear -->
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:justify-between sm:items-center">
        <div>
            <h1 class="text-2xl font-bold text-white">Gestión de cartas responsivas</h1>
            <p class="text-gray-400 mt-1">Administra las cartas responsivas del sistema</p>
        </div>
        <a href="{{ route('cartasresponsivas.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition-colors duration-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Crear carta responsiva
        </a>
    </div>



    <!-- Mensajes de sesión -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-600 text-white rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-600 text-white rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Filtros y búsqueda -->
    <div class="bg-gray-800 rounded-lg p-6 mb-6 border border-gray-700">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Búsqueda general -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Búsqueda general</label>
                <div class="relative">
                    <input 
                        wire:model.live="search" 
                        type="text" 
                        placeholder="Buscar por código, usuarios..." 
                        class="w-full pl-10 pr-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Filtro por código -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Código</label>
                <input 
                    wire:model.live="filterCodigo" 
                    type="text" 
                    placeholder="Filtrar por código..." 
                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>

            <!-- Filtro por fecha -->
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Fecha</label>
                <input 
                    wire:model.live="filterFecha" 
                    type="date" 
                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>

            <!-- Filtro por auditoría -->
<div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Auditoría</label>
                <select 
                    wire:model.live="filterAuditoria" 
                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                    <option value="">Todos</option>
                    <option value="1">Con Auditoría</option>
                    <option value="0">Sin Auditoría</option>
                </select>
            </div>
        </div>

        <!-- Botón limpiar filtros -->
        <div class="mt-4 flex justify-end">
            <button 
                wire:click="clearFilters" 
                class="px-4 py-2 text-gray-300 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors flex items-center gap-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Limpiar filtros
        </button>
        </div>
    </div>

    <!-- Tabla de cartas responsivas -->
    <div class="bg-gray-800 rounded-3xl overflow-hidden shadow-lg border border-gray-700">
        <div class="min-w-0 max-w-full w-full">
            <table class="responsive-table w-full divide-y divide-gray-700">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-600 transition-colors" wire:click="sortBy('codigo')">
                            <div class="flex items-center gap-2">
                                Código
                                @if($sortField === 'codigo')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($sortDirection === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        @endif
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-600 transition-colors" wire:click="sortBy('fecha')">
                            <div class="flex items-center gap-2">
                                Fecha
                                @if($sortField === 'fecha')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if($sortDirection === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        @endif
                                    </svg>
                                @endif
                            </div>
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Usuario
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Responsable
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Informática
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Artículos
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Auditoría
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-medium text-gray-300 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-gray-800 divide-y divide-gray-700">
                    @forelse($responsivas as $responsiva)
                        <tr class="hover:bg-gray-700 transition-colors">
                            <td data-label="Código @endif" class="px-6 py-4 whitespace-normal">
                                <div class="text-sm font-medium text-white">{{ $responsiva->codigo }}</div>
                                @if($responsiva->observacion)
                                    <div class="text-xs text-gray-400 mt-1">{{ Str::limit($responsiva->observacion, 50) }}</div>
                                @endif
                            </td>
                            <td data-label="Fecha @endif" class="px-6 py-4 whitespace-normal text-sm text-gray-300">
                                {{ $responsiva->fecha ? $responsiva->fecha->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td data-label="Usuario" class="px-6 py-4 whitespace-normal">
                                <div class="text-sm text-gray-300">
                                    @if($responsiva->user)
                                        <div class="font-medium text-white">{{ $responsiva->user->name }}</div>
                                        <div class="text-xs text-gray-400">{{ $responsiva->user->email }}</div>
                                    @else
                                        <span class="text-gray-500">Sin usuario</span>
                                    @endif
                                </div>
                            </td>
                            <td data-label="Responsable" class="px-6 py-4 whitespace-normal">
                                <div class="text-sm text-gray-300">
                                    @if($responsiva->responsable)
                                        <div class="font-medium text-white">{{ $responsiva->responsable->name }}</div>
                                        <div class="text-xs text-gray-400">{{ $responsiva->responsable->email }}</div>
                                    @else
                                        <span class="text-gray-500">Sin responsable</span>
                                    @endif
                                </div>
                            </td>
                            <td data-label="Informática" class="px-6 py-4 whitespace-normal">
                                <div class="text-sm text-gray-300">
                                    @if($responsiva->informatica)
                                        <div class="font-medium text-white">{{ $responsiva->informatica->name }}</div>
                                        <div class="text-xs text-gray-400">{{ $responsiva->informatica->email }}</div>
                                    @else
                                        <span class="text-gray-500">Sin informática</span>
                                    @endif
                                </div>
                            </td>
                            <td data-label="Artículos" class="px-6 py-4 whitespace-normal text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $responsiva->inventoryResponsivas->count() }} artículos
                                </span>
                            </td>
                            <td data-label="Auditoría" class="px-6 py-4 whitespace-normal text-center">
                                @if($responsiva->auditoria)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Sí
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        No
                                    </span>
                                @endif
                            </td>
                            <td data-label="Acciones" class="px-6 py-4 whitespace-normal text-center text-sm font-medium">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('cartasresponsivas.show', $responsiva->id) }}" 
                                       class="text-blue-400 hover:text-blue-300 transition-colors" 
                                       title="Ver detalles">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('cartasresponsivas.edit', $responsiva->id) }}" 
                                       class="text-yellow-400 hover:text-yellow-300 transition-colors" 
                                       title="Editar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>

                                    <a href="{{ route('cartasresponsiva.pdf', $responsiva->id) }}" 
                                       target="_blank"
                                       class="text-blue-400 hover:text-blue-300 transition-colors" 
                                       title="Descargar PDF">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                        </svg>
                                    </a>
                                    <button wire:click="delete({{ $responsiva->id }})" 
                                            wire:confirm="¿Estás seguro de que quieres eliminar esta carta responsiva?"
                                            class="text-red-400 hover:text-red-300 transition-colors" 
                                            title="Eliminar">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-lg">No se encontraron cartas responsivas</p>
                                <p class="text-sm">Intenta con otros términos de búsqueda o crea una nueva carta responsiva</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if($responsivas->hasPages())
            <div class="px-6 py-4 bg-gray-700 border-t border-gray-600">
                {{ $responsivas->links() }}
            </div>
        @endif
    </div>
</div>
