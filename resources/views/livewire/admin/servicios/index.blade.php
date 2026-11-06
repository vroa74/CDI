<div class="min-w-0 w-full p-1">
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


    <!-- Acordeón de Filtros -->
    <div x-data="{ open: true }" class="mb-1">
        <div @click="open = !open"
            class="flex items-center justify-between bg-blue-900 text-gray-200 px-3 py-1.5 rounded-t-3xl cursor-pointer select-none">
            <span class="font-semibold text-sm">Filtros</span>
            <svg :class="{ 'transform rotate-180': open }" class="w-4 h-4 transition-transform" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
        <div x-show="open" x-transition class="bg-blue-950 px-3 py-2 rounded-b-3xl border-t-0 border border-blue-800">
            <!-- Contenido de filtros para servicios -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
                <!-- Primera fila: Columna 1 - 3 inputs de texto -->
                <div class="min-w-0 py-3 bg-gray-900 border-2 border-green-400 rounded-3xl flex flex-col items-center justify-center gap-1 px-2">
                    <input type="text" wire:model.live="filterSolicitante" placeholder="Solicitante"
                        class="w-[90%] px-2 py-1 bg-gray-800 border border-green-400 rounded-lg text-green-200 placeholder-green-400 focus:ring-2 focus:ring-green-500 focus:border-transparent text-xs text-center">
                    <input type="text" wire:model.live="filterEfectuo" placeholder="Efectuó"
                        class="w-[90%] px-2 py-1 bg-gray-800 border border-green-400 rounded-lg text-green-200 placeholder-green-400 focus:ring-2 focus:ring-green-500 focus:border-transparent text-xs text-center">
                    <input type="text" wire:model.live="filterVobo" placeholder="VºBº"
                        class="w-[90%] px-2 py-1 bg-gray-800 border border-green-400 rounded-lg text-green-200 placeholder-green-400 focus:ring-2 focus:ring-green-500 focus:border-transparent text-xs text-center">
                </div>
                <!-- Columna 2: Inputs de ID arriba de inputs de fecha -->
                <div class="min-w-0 py-3 bg-gray-900 border-2 border-cyan-400 rounded-3xl flex flex-col items-center justify-center gap-1 px-1">
                    <!-- Filtros de ID -->
                    <div class="flex items-center justify-center gap-2 w-full">
                        <input type="number" wire:model.live="filterIdMin" placeholder="ID Mínimo"
                            class="min-w-0 flex-1 w-0 px-2 py-1 bg-gray-800 border border-pink-400 rounded-lg text-pink-200 placeholder-pink-400 focus:ring-2 focus:ring-pink-500 focus:border-transparent text-xs text-center"
                            min="1">
                        <span class="text-pink-300 font-medium text-xs flex-shrink-0">al</span>
                        <input type="number" wire:model.live="filterIdMax" placeholder="ID Máximo"
                            class="min-w-0 flex-1 w-0 px-2 py-1 bg-gray-800 border border-pink-400 rounded-lg text-pink-200 placeholder-pink-400 focus:ring-2 focus:ring-pink-500 focus:border-transparent text-xs text-center"
                            min="1">
                    </div>
                    <!-- Filtros de Fecha -->
                    <div class="flex items-center justify-center gap-2 w-full">
                        <input type="date" wire:model.live="filterFechaMin" placeholder="Fecha Mínima"
                            class="min-w-0 flex-1 w-0 px-1 py-1 bg-gray-800 border border-cyan-400 rounded-lg text-cyan-200 placeholder-cyan-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent text-xs text-center">
                        <span class="text-cyan-300 font-medium text-xs flex-shrink-0">al</span>
                        <input type="date" wire:model.live="filterFechaMax" placeholder="Fecha Máxima"
                            class="min-w-0 flex-1 w-0 px-1 py-1 bg-gray-800 border border-cyan-400 rounded-lg text-cyan-200 placeholder-cyan-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent text-xs text-center">
                    </div>
                </div>
                <div class="min-w-0 py-3 bg-gray-900 border-2 border-yellow-400 rounded-3xl flex flex-col items-center justify-center gap-1 px-2">
                    <div class="text-yellow-300 font-medium text-sm mb-0.5">Registros por página</div>
                    <select wire:model.live="perPage"
                        class="w-11/12 px-2 py-1 bg-gray-800 border border-yellow-400 rounded-lg text-yellow-200 focus:ring-2 focus:ring-yellow-500 focus:border-transparent text-sm">
                        <option value="5">5 por página</option>
                        <option value="10">10 por página</option>
                        <option value="15">15 por página</option>
                        <option value="25">25 por página</option>
                        <option value="30">30 por página</option>
                        <option value="35">35 por página</option>
                        <option value="45">40 por página</option>
                        <option value="50">50 por página</option>
                        <option value="100">100 por página</option>
                        <option value="200">200 por página</option>
                    </select>
                </div>
                <div class="min-w-0 py-3 bg-gray-900 border-2 border-pink-400 rounded-3xl flex flex-col items-center justify-center gap-1 px-2">
                    <div class="text-pink-300 font-medium text-sm mb-0.5">Tipo de servicio</div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 w-full">
                        <label class="flex items-center text-xs text-pink-200">
                            <input type="checkbox" wire:model.live="filterCorrectivo" class="mr-1">
                            Correctivo
                        </label>
                        <label class="flex items-center text-xs text-pink-200">
                            <input type="checkbox" wire:model.live="filterPreventivo" class="mr-1">
                            Preventivo
                        </label>
                        <label class="flex items-center text-xs text-pink-200">
                            <input type="checkbox" wire:model.live="filterTransparencia" class="mr-1">
                            Transparencia
                        </label>
                        <label class="flex items-center text-xs text-pink-200">
                            <input type="checkbox" wire:model.live="filterATecnico" class="mr-1">
                            A. Técnico
                        </label>
                        <label class="flex items-center text-xs text-pink-200">
                            <input type="checkbox" wire:model.live="filterWebIns" class="mr-1">
                            Web/Ins
                        </label>
                        <label class="flex items-center text-xs text-pink-200">
                            <input type="checkbox" wire:model.live="filterPrint" class="mr-1">
                            Print
                        </label>
                    </div>
                </div>
                <div class="min-w-0 py-3 bg-gray-900 border-2 border-cyan-400 rounded-3xl flex flex-col items-center justify-center gap-1 px-2">
                    <div class="text-cyan-300 font-medium text-sm mb-0.5">Vía de solicitud</div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 w-full">
                        <label class="flex items-center text-xs text-cyan-200">
                            <input type="checkbox" wire:model.live="filterEmail" class="mr-1">
                            Correo
                        </label>
                        <label class="flex items-center text-xs text-cyan-200">
                            <input type="checkbox" wire:model.live="filterTelefono" class="mr-1">
                            Teléfono
                        </label>
                        <label class="flex items-center text-xs text-cyan-200">
                            <input type="checkbox" wire:model.live="filterSolicitudServicio" class="mr-1">
                            Solicitud
                        </label>
                        <label class="flex items-center text-xs text-cyan-200">
                            <input type="checkbox" wire:model.live="filterOficio" class="mr-1">
                            Oficio
                        </label>
                        <label class="flex items-center text-xs text-cyan-200">
                            <input type="checkbox" wire:model.live="filterCalendario" class="mr-1">
                            Calendario
                        </label>
                    </div>
                </div>
                <div class="min-w-0 py-3 bg-gray-900 border-2 border-yellow-400 rounded-3xl flex flex-col items-center justify-center gap-1 px-2">
                    <div class="text-lg font-bold text-yellow-300">
                        {{ $services->total() }}
                    </div>
                    <span class="text-xs text-yellow-200 font-normal text-center">
                        Total de servicios
                    </span>
                    <div class="flex gap-1 w-full">
                        @if (auth()->user()->tipo !== 3)
                            <a href="{{ route('servicios.create') }}"
                                class="flex-1 px-1 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center justify-center gap-1 transition-colors text-xs font-medium">
                                <x-lucide name="plus" class="w-3 h-3" />
                                Agregar
                            </a>
                        @endif
                        @if (auth()->user()->tipo !== 3)
                            <button wire:click="openReportModal('general')"
                                class="flex-1 px-1 py-1 bg-green-600 hover:bg-green-700 text-white rounded-lg flex items-center justify-center gap-1 transition-colors text-xs font-medium"
                                title="Generar Reporte">
                                <x-lucide name="file-text" class="w-3 h-3" />
                                Reportes
                            </button>
                            <button wire:click="exportServices"
                                class="flex-1 px-1 py-1 bg-purple-600 hover:bg-purple-700 text-white rounded-lg flex items-center justify-center gap-1 transition-colors text-xs font-medium"
                                title="Exportar Servicios">
                                <x-lucide name="download" class="w-3 h-3" />
                                Exportar
                            </button>
                        @endif
                        <button wire:click="toggleIdSorting"
                            class="flex-1 px-1 py-1 bg-orange-600 hover:bg-orange-700 text-white rounded-lg flex items-center justify-center gap-1 transition-colors text-xs font-medium"
                            title="Ordenar por ID (Toggle)">
                            <x-lucide name="{{ $this->idSortIcon }}" class="w-3 h-3" />
                            {{ $this->idSortText }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
 
    </div>

    <!-- Tabla de servicios -->
    <div class="bg-blue-800 rounded-3xl overflow-hidden shadow-lg border border-blue-700">
        <div class="min-w-0 w-full">
            <table class="responsive-table w-full divide-y divide-gray-700 rounded-3xl overflow-hidden">
                <thead class="bg-blue-900">
                    <tr>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider w-[8%] rounded-tl-3xl">
                            ID Servicio
                        </th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider w-[10%]">
                            Fecha de servicio
                        </th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider w-[18%]">
                            Involucrados
                        </th>
                        <th class="px-3 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider w-[12%]">
                            Objetivo de la solicitud
                        </th>
                        <th class="px-3 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider w-[12%]">
                            Actividades realizadas
                        </th>
                        <th class="px-3 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider w-[12%]">
                            Observaciones
                        </th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider w-[8%]">
                            Estado
                        </th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider w-[9%] rounded-tr-3xl">
                            Acciones
                        </th>
                    </tr>
                </thead>
                
                <tbody class="bg-gray-700 divide-y divide-gray-600">
                    
                    @forelse($services as $service)
                        @php
                            $bgColor = 'bg-gray-700';
                            $hoverColor = 'hover:bg-gray-600';
                            
                            // Prioridad 1: Preventivo y Calendario marcados
                            if ($service->preventivo && $service->calendario) {
                                $bgColor = 'bg-blue-950';
                                // Si además está impreso, cambiar hover a blue-800
                                if ($service->impressions) {
                                    $hoverColor = 'hover:bg-blue-800';
                                } else {
                                    $hoverColor = 'hover:bg-blue-950';
                                }
                            }
                            // Prioridad 2: Estado e impresiones
                            elseif ($service->status && $service->impressions) {
                                $bgColor = 'bg-black';
                                $hoverColor = 'hover:bg-green-950';
                            } elseif ($service->status && !$service->impressions) {
                                $bgColor = 'bg-green-800';
                                $hoverColor = 'hover:bg-green-700';
                            }
                        @endphp
                        <tr wire:key="service-{{ $service->id }}" class="{{ $bgColor }} {{ $hoverColor }} transition-colors">
                            <td data-label="Servicio" class="px-3 py-4 text-sm text-gray-300 whitespace-normal">
                                id: {{ $service->id ?? 'N/A' }} <br> 
                                serv:{{ $service->id_s ?? 'N/A' }} <br> 
                                <span class="text-xs text-red-600"> {  <span  class="text-10px text-yellow-300"> {{ $this->NameQrCode($service->id_s) }}  </span>   } </span>
                            </td>
                            <td data-label="Fecha" class="px-3 py-4 text-sm text-gray-300 whitespace-normal">
                                {{ $service->F_serv ? $service->F_serv->format('d/m/Y') : 'N/A' }}
                            </td>
                            <td data-label="Participantes" class="px-3 py-4 text-sm text-gray-300">
                                <div class="space-y-1">
                                    @if($service->solicitante)
                                        <div class="text-xs text-gray-400">Solicitante:</div>
                                        <div class="font-medium text-white">{{ $service->solicitante->name }}</div>
                                    @endif
                                    @if($service->efectuo)
                                        <div class="text-xs text-gray-400">Efectuó:</div>
                                        <div class="font-medium text-white">{{ $service->efectuo->name }}</div>
                                    @endif
                                    @if($service->vobo)
                                        <div class="text-xs text-gray-400">VºBº:</div>
                                        <div class="font-medium text-white">{{ $service->vobo->name }}</div>
                                    @endif
                                    @if(!$service->solicitante && !$service->efectuo && !$service->vobo)
                                        <div class="text-gray-500">N/A</div>
                                    @endif
                                </div>
                            </td>
                            <td data-label="Objetivo de la solicitud" class="px-3 py-4 text-sm text-gray-300">
                                <div class="text-center">
                                    <x-service-text :value="$service->obj_sol" expandable />
                                </div>
                            </td>
                            <td data-label="Actividades realizadas" class="px-3 py-4 text-sm text-gray-300">
                                <div class="text-center">
                                    <x-service-text :value="$service->actividades" expandable />
                                </div>
                            </td>
                            <td data-label="Observaciones" class="px-3 py-4 text-sm text-gray-300">
                                <div class="text-center">
                                    <x-service-text :value="$service->observaciones" expandable />
                                </div>
                            </td>
                            <td data-label="Estado" class="px-3 py-4 text-center">
                                @can('manage-services')
                                @if($service->status)
                                    <button 
                                        wire:click="toggleStatus({{ $service->id }})"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 hover:bg-green-200 transition-colors cursor-pointer"
                                        title="Haz clic para desactivar"
                                    >
                                        Activo
                                    </button>
                                @else
                                    <button 
                                        wire:click="toggleStatus({{ $service->id }})"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-black hover:bg-gray-200 transition-colors cursor-pointer"
                                        title="Haz clic para activar"
                                    >
                                        Inactivo
                                    </button>
                                @endif
                                                            @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $service->status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-black' }}">
                                        {{ $service->status ? 'Activo' : 'Inactivo' }}
                                    </span>
                                @endcan
                            </td>
                            <td data-label="Acciones" class="px-3 py-4">
                                <div class="flex items-center justify-center gap-1">
                                    @can('manage-services')
                                    <button 
                                        wire:click="editService({{ $service->id }})"
                                        class="text-blue-400 hover:text-blue-300 transition-colors"
                                        title="Editar"
                                    >
                                        <x-lucide name="edit" class="w-4 h-4" />
                                    </button>
                                    @endcan
                                    {{-- Reportede Servicio Normal --}}
                                    @if (auth()->user()->tipo !== 3)
                                    <button 
                                        wire:click="generateIndividualServiceReport({{ $service->id }})"
                                        class="text-orange-400 hover:text-red-500 transition-colors"
                                        title="Generar el Reporte del Servicio">
                                        <i class="ri-printer-line"></i>
                                    </button> 
                                    {{-- Reporte de servicio calendarizado --}}

                                    @if($service->preventivo && $service->calendario) 
                                    <button 
                                        wire:click="generateIndividualServiceReportCal({{ $service->id }})"
                                        class="text-green-400 hover:text-green-500 transition-colors"
                                        title="Generar el Reporte del Servicio Calendarizado">
                                        <i class="ri-printer-line"></i>
                                    </button>
                                    {{-- Reporte de servicio detallado de servico con fotos --}}                                                                        
                                    @endif
                                    @if($service->photos && $service->photos->count() > 0)
                                    <button 
                                        wire:click="generateDetailsServiceReport({{ $service->id }})"
                                        class="text-blue-400 hover:text-blue-300 transition-colors"
                                        title="Generar Reporte Detallado">
                                        <i class="ri-printer-line"></i>
                                    </button>
                                    @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-3 py-12 text-center text-gray-400">
                                <x-lucide name="wrench" class="w-12 h-12 mx-auto mb-4 text-gray-600" />
                                <p class="text-lg">No se encontraron servicios</p>
                                <p class="text-sm">Comienza agregando un nuevo servicio</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        @if($services->hasPages())
            <div class="px-3 py-4 bg-gray-700 border-t border-gray-600">
                {{ $services->links() }}
            </div>
        @endif
    </div>

    <!-- Modal de Reportes -->
    @if($showReportModal)
        <div class="app-modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-white">Generar reporte</h3>
                    <button wire:click="closeReportModal" class="modal-action text-white bg-gray-600 hover:bg-gray-700">
                        <x-lucide name="x" class="w-6 h-6" />
                    </button>
                </div>

                <form wire:submit.prevent="generateReport" class="space-y-4">
                    <!-- Tipo de Reporte -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Tipo de reporte</label>
                        <select wire:model.defer="reportType" wire:blur="$refresh" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="general">Reporte General</option>
                            <option value="por_usuario">Por Usuario</option>
                            <option value="por_tipo">Por Tipo de Servicio</option>
                            <option value="por_fecha">Por Fecha</option>
                        </select>
                    </div>

                    <!-- Rango de Fechas -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Desde</label>
                            <input wire:model.defer="reportDateFrom" wire:blur="$refresh" type="date" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Hasta</label>
                            <input wire:model.defer="reportDateTo" wire:blur="$refresh" type="date" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                    </div>

                    <!-- Filtro por Usuario -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Usuario (opcional)</label>
                        <select wire:model.defer="reportUser" wire:blur="$refresh" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Todos los usuarios</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filtro por Estado -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Estado (opcional)</label>
                        <select wire:model.defer="reportStatus" wire:blur="$refresh" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Todos los estados</option>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>

                    <!-- Botones -->
                    <div class="flex gap-3 pt-4">
                        <button type="button" wire:click="closeReportModal" class="modal-action text-white flex-1 px-4 py-2 bg-gray-600 hover:bg-gray-700  rounded-lg transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" class="modal-action text-white flex-1 px-4 py-2 bg-green-600 hover:bg-green-700  rounded-lg transition-colors flex items-center justify-center gap-2">
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
            console.log('Livewire initialized - Cache busted at: ' + new Date().toISOString());
            
            
            // Escuchar evento de recarga de página
            Livewire.on('reload-page', () => {
                console.log('Recargando página debido a cambio en filtros críticos...');
                // Recargar con parámetros para mantener los filtros
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('refresh', Date.now());
                window.location.href = currentUrl.toString();
            });
            
            Livewire.on('download-report', (event) => {
                console.log('Download event received:', event);
                const url = event.url;
                console.log('Download URL:', url);
                
                const link = document.createElement('a');
                link.href = url;
                link.download = '';
                link.target = '_blank';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                console.log('Download link clicked');
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

    <style>
        /* Estilos para la funcionalidad de expandir/contraer texto */
        .text-expandable {
            transition: all 0.3s ease;
        }
        
        .text-expandable span {
            display: inline-block;
            transition: opacity 0.3s ease;
        }
        
        .expand-button {
            cursor: pointer;
            user-select: none;
            transition: all 0.2s ease;
        }
        
        .expand-button:hover {
            transform: scale(1.05);
        }
        
        /* Mejorar la legibilidad del texto truncado */
        .text-truncated {
            line-height: 1.4;
            word-wrap: break-word;
            hyphens: auto;
        }
    </style>
</div> 