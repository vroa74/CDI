<div class="p-4">
    <!-- Acordeón de Filtros Compacto -->
    <div x-data="{ open: true }" class="mb-4">
        <div @click="open = !open"
            class="flex items-center justify-between bg-blue-900 text-gray-200 px-4 py-2 rounded-t-xl cursor-pointer select-none">
            <span class="font-semibold text-sm">Filtros</span>
            <svg :class="{ 'transform rotate-180': open }" class="w-4 h-4 transition-transform" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
        <div x-show="open" x-transition class="bg-blue-950 px-4 py-2 rounded-b-xl border-t-0 border border-blue-800">
            <!-- Tabla de filtros dentro del acordeón -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
                <div
                    class="min-h-12 py-3 bg-gray-900 border-2 border-pink-400 rounded-xl flex flex-col items-center justify-center gap-0.5">
                    <input type="text" wire:model.live="filterUserName" placeholder="Usuario"
                        class="w-11/12 px-1 py-0.5 bg-gray-800 border border-pink-400 rounded text-pink-200 placeholder-pink-400 focus:ring-1 focus:ring-pink-500 focus:border-transparent text-xs">
                    <input type="text" wire:model.live="filterResponsibleName" placeholder="Responsable"
                        class="w-11/12 px-1 py-0.5 bg-gray-800 border border-pink-400 rounded text-pink-200 placeholder-pink-400 focus:ring-1 focus:ring-pink-500 focus:border-transparent text-xs">
                </div>
                <div
                    class="min-h-12 py-3 bg-gray-900 border-2 border-cyan-400 rounded-xl flex flex-col items-center justify-center gap-0.5">
                    <input type="text" wire:model.live="filterNi" placeholder="NI"
                        class="w-11/12 px-1 py-0.5 bg-gray-800 border border-cyan-400 rounded text-cyan-200 placeholder-cyan-400 focus:ring-1 focus:ring-cyan-500 focus:border-transparent text-xs">
                    <input type="text" wire:model.live="filterDireccion" placeholder="Dirección"
                        class="w-11/12 px-1 py-0.5 bg-gray-800 border border-cyan-400 rounded text-cyan-200 placeholder-cyan-400 focus:ring-1 focus:ring-cyan-500 focus:border-transparent text-xs">
                </div>
                <div
                    class="min-h-12 py-3 bg-gray-900 border-2 border-yellow-400 rounded-xl flex flex-col items-center justify-center gap-0.5">
                    <input type="text" wire:model.live="filterNs" placeholder="NS"
                        class="w-11/12 px-1 py-0.5 bg-gray-800 border border-yellow-400 rounded text-yellow-200 placeholder-yellow-400 focus:ring-1 focus:ring-yellow-500 focus:border-transparent text-xs">
                    <input type="text" wire:model.live="filterArticulo" placeholder="Artículo"
                        class="w-11/12 px-1 py-0.5 bg-gray-800 border border-yellow-400 rounded text-yellow-200 placeholder-yellow-400 focus:ring-1 focus:ring-yellow-500 focus:border-transparent text-xs">
                </div>
                <div
                    class="min-h-12 py-3 bg-gray-900 border-2 border-pink-400 rounded-xl flex flex-col items-center justify-center gap-0.5">
                    <input type="text" wire:model.live="filterMarca" placeholder="Marca"
                        class="w-11/12 px-1 py-0.5 bg-gray-800 border border-pink-400 rounded text-pink-200 placeholder-pink-400 focus:ring-1 focus:ring-pink-500 focus:border-transparent text-xs">
                    <input type="text" wire:model.live="filterModelo" placeholder="Modelo"
                        class="w-11/12 px-1 py-0.5 bg-gray-800 border border-pink-400 rounded text-pink-200 placeholder-pink-400 focus:ring-1 focus:ring-pink-500 focus:border-transparent text-xs">
                </div>
                <div
                    class="min-h-12 py-3 bg-gray-900 border-2 border-cyan-400 rounded-xl flex flex-col items-center justify-center gap-0.5">
                    <select wire:model.live="filterFechaInv"
                        class="w-11/12 px-1 py-0.5 bg-gray-800 border border-cyan-400 rounded text-cyan-200 focus:ring-1 focus:ring-cyan-500 focus:border-transparent text-xs">
                        <option value="">Todas las fechas</option>
                        @foreach ($uniqueFechasInv as $fecha)
                            <option value="{{ $fecha }}">{{ $fecha }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="perPage"
                        class="w-11/12 px-1 py-0.5 bg-gray-800 border border-cyan-400 rounded text-cyan-200 focus:ring-1 focus:ring-cyan-500 focus:border-transparent text-xs">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div
                    class="min-h-12 py-3 bg-gray-900 border-2 border-yellow-400 rounded-xl flex flex-col items-center justify-center text-sm font-bold text-yellow-300">
                    <div class="text-sm font-bold text-yellow-300 mb-1">
                        {{ $inventories->total() }} Registros
                    </div>
                    <div class="flex items-center gap-1">
                        <button
                            class="bg-gray-500 text-gray-300 px-1 py-0.5 rounded text-xs font-medium flex items-center gap-0.5 cursor-not-allowed opacity-50"
                            disabled>
                            <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                            Usuario
                        </button>
                        <a href="{{ route('inventario.create') }}"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-1 py-0.5 rounded text-xs font-medium flex items-center gap-0.5 transition-colors">
                            <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            Agregar
                        </a>
                        <a href="{{ route('inventario.export.html', ['filters' => json_encode([
                            'search' => $search,
                            'filterNi' => $filterNi,
                            'filterDireccion' => $filterDireccion,
                            'filterUserName' => $filterUserName,
                            'filterResponsibleName' => $filterResponsibleName,
                            'filterNs' => $filterNs,
                            'filterArticulo' => $filterArticulo,
                            'filterMarca' => $filterMarca,
                            'filterModelo' => $filterModelo,
                            'filterFechaInv' => $filterFechaInv
                        ])]) }}"
                            class="bg-green-600 hover:bg-green-700 text-white px-1 py-0.5 rounded text-xs font-medium flex items-center gap-0.5 transition-colors">
                            <svg class="w-2 h-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Exportar
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filtro adicional por año de servicio -->
            <div class="mt-2">
                <div
                    class="bg-gray-900 border-2 border-green-400 rounded-xl flex flex-col items-center justify-center p-2">
                    <label class="text-green-300 text-xs font-semibold mb-1">Filtrar por Año de Servicio</label>
                    <select wire:model.live="filterServiceYear"
                        class="w-full px-2 py-1 bg-gray-800 border border-green-400 rounded text-green-200 focus:ring-1 focus:ring-green-500 focus:border-transparent text-xs">
                        <option value="">Todos los años</option>
                        @foreach ($uniqueServiceYears as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensaje de éxito -->
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
            class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex justify-between items-center">
            <span>{{ session('message') }}</span>
            <button @click="show = false" class="text-green-700 hover:text-green-900">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    <!-- Mensaje de error -->
    @if (session()->has('error'))
        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <!-- Tabla de inventario -->
    <div class="bg-gray-800 rounded-3xl overflow-hidden shadow-lg border border-gray-700">
        <div class="min-w-0 max-w-full w-full">
            <table class="responsive-table w-full divide-y divide-gray-700 rounded-3xl overflow-hidden">
                <thead class="bg-gray-700">
                    <tr>
                        <th
                            class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider w-[15%] rounded-tl-3xl">
                            Artículo
                        </th>
                        <th
                            class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider w-[15%]">
                            Detalles
                        </th>
                        <th
                            class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider w-[20%]">
                            Resguardante
                        </th>
                        <th
                            class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider w-[20%]">
                            Usuarios
                        </th>
                        <th
                            class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider w-[10%]">
                            Tipo
                        </th>
                        <th
                            class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider w-[15%]">
                            Servicios Asociados
                        </th>
                        <th
                            class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider w-[10%]">
                            Estado
                        </th>
                        <th
                            class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider w-[10%] rounded-tr-3xl">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-gray-800 divide-y divide-gray-700">
                    @forelse($inventories as $item)
                        <tr class="hover:bg-gray-700 transition-colors">
                            <td data-label="Artículo" class="px-3 py-4 text-sm text-gray-300">
                                <div class="font-medium text-white">{{ $item->articulo ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-400">NI: {{ $item->ni ?? 'N/A' }}</div>
                                <div class="text-xs text-yellow-300">ID: {{ $item->id ?? 'N/A' }}</div>
                            </td>
                            <td data-label="Detalles" class="px-3 py-4 text-sm text-gray-300">
                                <div class="space-y-1">
                                    <div><span class="font-semibold">NS:</span> {{ $item->ns ?? 'N/A' }}</div>
                                    <div><span class="font-semibold">Marca:</span> {{ $item->marca ?? 'N/A' }}</div>
                                    <div><span class="font-semibold">Modelo:</span> {{ $item->modelo ?? 'N/A' }}</div>
                                </div>
                            </td>
                            <td data-label="Resguardante" class="px-3 py-4 text-sm text-gray-300">
                                <div class="font-medium text-white">
                                    @if ($item->responsible)
                                        {{ $item->responsible->name ?? 'SN' }}
                                    @else
                                        SN
                                    @endif
                                </div>
                                <div class="text-xs text-gray-400">
                                    @if ($item->responsible && $item->responsible->position)
                                        {{ $item->responsible->position }}
                                    @else
                                        S/C
                                    @endif
                                </div>
                            </td>
                            <td data-label="Usuarios" class="px-3 py-4 text-sm text-gray-300">
                                <div class="space-y-2">
                                    @if ($item->assignedUser)
                                        <div>
                                            <div class="text-xs text-gray-400">Usuario:</div>
                                            <div class="font-medium text-yellow-400">
                                                {{ $item->assignedUser->name ?? 'N/A' }}</div>
                                            <div class="text-xs text-yellow-300">
                                                {{ $item->assignedUser->position ?? 'Sin posición' }}</div>
                                        </div>
                                    @endif
                                    @if ($item->responsible)
                                        <div>
                                            <div class="text-xs text-gray-400">Resguardante:</div>
                                            <div class="font-medium text-pink-400">
                                                {{ $item->responsible->name ?? 'N/A' }}</div>
                                            <div class="text-xs text-pink-300">
                                                {{ $item->responsible->position ?? 'Sin posición' }}</div>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td data-label="Tipo" class="px-3 py-4 text-center">
                                @if ($item->is_pc)
                                    <span
                                        class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        PC
                                    </span>
                                @else
                                    <span
                                        class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Otro
                                    </span>
                                @endif
                            </td>
                            <td data-label="Servicios Asociados" class="px-3 py-4 text-sm text-gray-300">
                                @if ($item->services && $item->services->count() > 0)
                                    <div class="space-y-1">
                                        @foreach ($item->services as $service)
                                            <div class="bg-blue-900 rounded-lg p-2 border border-blue-700">
                                                <div class="text-xs text-blue-200 font-semibold">
                                                    Servicio #{{ $service->id }}
                                                </div>
                                                <div class="text-xs text-blue-300">
                                                    @if ($service->solicitante)
                                                        <div><span class="font-semibold">Usuario:</span>
                                                            {{ $service->solicitante->name }}</div>
                                                        <div><span class="font-semibold">Cargo:</span>
                                                            {{ $service->solicitante->position ?? 'S/C' }}</div>
                                                        <div><span class="font-semibold">Dirección:</span>
                                                            {{ $service->solicitante->direction ?? 'S/C' }}</div>
                                                    @endif
                                                    <div><span class="font-semibold">Fecha:</span>
                                                        {{ $service->pivot->service_date ?? 'S/F' }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center text-gray-500 text-xs">
                                        Sin servicios asociados
                                    </div>
                                @endif
                            </td>
                            <td data-label="Estado" class="px-3 py-4 text-center">
                                @if ($item->status)
                                    <button wire:click="toggleStatus({{ $item->id }})"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 hover:bg-green-200 transition-colors cursor-pointer"
                                        title="Haz clic para desactivar">
                                        Activo
                                    </button>
                                @else
                                    <button wire:click="toggleStatus({{ $item->id }})"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-black hover:bg-gray-200 transition-colors cursor-pointer"
                                        title="Haz clic para activar">
                                        Inactivo
                                    </button>
                                @endif
                            </td>
                            <td data-label="Acciones" class="px-3 py-4">
                                <div class="flex items-center justify-center gap-1">
                                    <button wire:click="editInventory({{ $item->id }})"
                                        class="text-blue-400 hover:text-blue-300 transition-colors" title="Editar">
                                        <x-lucide name="edit" class="w-4 h-4" />
                                    </button>
                                    <button wire:click="generateIndividualInventoryReport({{ $item->id }})"
                                        class="text-orange-400 hover:text-red-500 transition-colors"
                                        title="Generar el Reporte del Artículo">
                                        <i class="ri-printer-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-3 py-12 text-center text-gray-400">
                                <x-lucide name="database-zap" class="w-12 h-12 mx-auto mb-4 text-gray-600" />
                                <p class="text-lg">No se encontraron artículos en el inventario</p>
                                <p class="text-sm">Comienza agregando un nuevo artículo</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        @if ($inventories->hasPages())
            <div class="px-3 py-4 bg-gray-700 border-t border-gray-600">
                {{ $inventories->links() }}
            </div>
        @endif
    </div>

    <!-- Modal de Reportes -->
    @if ($showReportModal)
        <div class="app-modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-white">Generar Reporte</h3>
                    <button wire:click="closeReportModal" class="modal-action text-white bg-gray-600 hover:bg-gray-700">
                        <x-lucide name="x" class="w-6 h-6" />
                    </button>
                </div>

                <form wire:submit.prevent="generateReport" class="space-y-4">
                    <!-- Tipo de Reporte -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Tipo de Reporte</label>
                        <select wire:model="reportType"
                            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="general">Reporte General</option>
                            <option value="por_usuario">Por Usuario</option>
                            <option value="por_tipo">Por Tipo de Artículo</option>
                            <option value="por_fecha">Por Fecha</option>
                        </select>
                    </div>

                    <!-- Rango de Fechas -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Desde</label>
                            <input wire:model="reportDateFrom" type="date"
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Hasta</label>
                            <input wire:model="reportDateTo" type="date"
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <!-- Filtro por Usuario -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Usuario (Opcional)</label>
                        <select wire:model="reportUser"
                            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Todos los usuarios</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro por Estado -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Estado (Opcional)</label>
                        <select wire:model="reportStatus"
                            class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Todos los estados</option>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>

                    <!-- Botones -->
                    <div class="flex gap-3 pt-4">
                        <button type="button" wire:click="closeReportModal"
                            class="modal-action text-white flex-1 px-4 py-2 bg-gray-600 hover:bg-gray-700  rounded-lg transition-colors">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="modal-action text-white flex-1 px-4 py-2 bg-green-600 hover:bg-green-700  rounded-lg transition-colors flex items-center justify-center gap-2">
                            <x-lucide name="download" class="w-4 h-4" />
                            Generar PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Script para manejar la descarga -->
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('download-report', (event) => {
                const url = event.url;
                const link = document.createElement('a');
                link.href = url;
                link.download = '';
                link.target = '_blank';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });
        });
    </script>

    <!-- Scripts para abrir PDF en nueva pestaña -->
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('openPdfInNewTab', (event) => {
                window.open(event.url, '_blank');
            });
        });
    </script>
</div>
