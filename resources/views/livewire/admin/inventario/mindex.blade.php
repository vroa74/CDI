<div class="p-2 sm:p-4">
    <!-- Header móvil con información del dispositivo -->
    <div class="mb-4 p-3 bg-blue-900 rounded-lg text-white text-center">
        <h1 class="text-lg font-bold">📱 Inventario Móvil</h1>
        <p class="text-xs text-blue-200">Dispositivo: {{ $deviceType }}</p>
    </div>

    <!-- Filtros móviles colapsables -->
    <div x-data="{ open: false }" class="mb-4">
        <button @click="open = !open"
            class="w-full flex items-center justify-between bg-blue-800 text-white px-4 py-3 rounded-lg">
            <span class="font-semibold">🔍 Filtros</span>
            <svg :class="{ 'rotate-180': open }" class="w-5 h-5 transition-transform" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
        
        <div x-show="open" x-transition class="mt-2 bg-gray-800 rounded-lg p-3 space-y-3">
            <!-- Búsqueda principal -->
            <div class="relative">
                <input wire:model.live="search" type="text" placeholder="🔍 Buscar artículo, NI, NS..."
                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 text-sm">
            </div>
            
            <!-- Filtros en grid móvil -->
            <div class="grid grid-cols-2 gap-2">
                <input wire:model.live="filterNi" type="text" placeholder="NI"
                    class="px-2 py-1 bg-gray-700 border border-gray-600 rounded text-white placeholder-gray-400 text-xs">
                <input wire:model.live="filterNs" type="text" placeholder="NS"
                    class="px-2 py-1 bg-gray-700 border border-gray-600 rounded text-white placeholder-gray-400 text-xs">
                <input wire:model.live="filterArticulo" type="text" placeholder="Artículo"
                    class="px-2 py-1 bg-gray-700 border border-gray-600 rounded text-white placeholder-gray-400 text-xs">
                <input wire:model.live="filterMarca" type="text" placeholder="Marca"
                    class="px-2 py-1 bg-gray-700 border border-gray-600 rounded text-white placeholder-gray-400 text-xs">
            </div>
            
            <!-- Selects -->
            <div class="grid grid-cols-2 gap-2">
                <select wire:model.live="filterFechaInv" class="px-2 py-1 bg-gray-700 border border-gray-600 rounded text-white text-xs">
                    <option value="">Todas las fechas</option>
                    @foreach ($uniqueFechasInv as $fecha)
                        <option value="{{ $fecha }}">{{ $fecha }}</option>
                    @endforeach
                </select>
                <select wire:model.live="perPage" class="px-2 py-1 bg-gray-700 border border-gray-600 rounded text-white text-xs">
                    <option value="10">10 items</option>
                    <option value="25">25 items</option>
                    <option value="50">50 items</option>
                </select>
            </div>
            
            <!-- Contador -->
            <div class="text-center p-2 bg-yellow-900 rounded text-yellow-200 text-sm font-bold">
                {{ $inventories->total() }} artículos
            </div>
        </div>
    </div>

    <!-- Mensajes de estado -->
    @if (session()->has('message'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
            class="mb-3 p-3 bg-green-800 border border-green-600 text-green-200 rounded-lg text-sm">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-3 p-3 bg-red-800 border border-red-600 text-red-200 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Botones de acción móviles -->
    <div class="flex flex-wrap gap-2 mb-4 min-w-0 max-w-full w-full pb-2">
        <a href="{{ route('inventory.user-inv') }}"
            class="flex-shrink-0 px-4 py-2 bg-purple-600 text-white rounded-lg text-sm font-medium">
            👥 Usuarios
        </a>
        <a href="{{ route('inventario.create') }}"
            class="flex-shrink-0 px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium">
            ➕ Agregar
        </a>
        <button wire:click="openReportModal('general')"
            class="flex-shrink-0 px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium">
            📊 Reportes
        </button>
    </div>

    <!-- Lista de inventario móvil -->
    <div class="space-y-3">
        @forelse($inventories as $item)
            <div class="bg-gray-800 rounded-lg p-3 border border-gray-700">
                <!-- Header del item -->
                <div class="flex justify-between items-start mb-2">
                    <div class="flex-1">
                        <h3 class="font-bold text-white text-sm">{{ $item->articulo ?? 'N/A' }}</h3>
                        <p class="text-gray-400 text-xs">NI: {{ $item->ni ?? 'N/A' }}</p>
                    </div>
                    <div class="flex gap-1">
                        @if ($item->is_pc)
                            <span class="px-2 py-1 bg-blue-600 text-white text-xs rounded">💻 PC</span>
                        @endif
                        <span class="px-2 py-1 {{ $item->status ? 'bg-green-600' : 'bg-red-600' }} text-white text-xs rounded">
                            {{ $item->status ? '✅ Activo' : '❌ Inactivo' }}
                        </span>
                    </div>
                </div>

                <!-- Detalles del item -->
                <div class="grid grid-cols-2 gap-2 text-xs text-gray-300 mb-3">
                    <div>
                        <span class="font-medium">NS:</span> {{ $item->ns ?? 'N/A' }}
                    </div>
                    <div>
                        <span class="font-medium">Marca:</span> {{ $item->marca ?? 'N/A' }}
                    </div>
                    <div>
                        <span class="font-medium">Modelo:</span> {{ $item->modelo ?? 'N/A' }}
                    </div>
                    <div>
                        <span class="font-medium">Fecha:</span> {{ $item->fecha_inv ?? 'N/A' }}
                    </div>
                </div>

                <!-- Usuario asignado -->
                @if($item->assignedUser)
                    <div class="mb-2 p-2 bg-gray-700 rounded text-xs">
                        <span class="font-medium text-blue-300">👤 Usuario:</span> 
                        <span class="text-white">{{ $item->assignedUser->name }}</span>
                    </div>
                @endif

                <!-- Responsable -->
                @if($item->responsible)
                    <div class="mb-2 p-2 bg-gray-700 rounded text-xs">
                        <span class="font-medium text-green-300">🏢 Responsable:</span> 
                        <span class="text-white">{{ $item->responsible->name }}</span>
                    </div>
                @endif

                <!-- Botones de acción -->
                <div class="flex gap-2 mt-3">
                    <button wire:click="editInventory({{ $item->id }})"
                        class="flex-1 px-3 py-2 bg-blue-600 text-white rounded text-xs font-medium">
                        ✏️ Editar
                    </button>
                    <button wire:click="toggleStatus({{ $item->id }})"
                        class="flex-1 px-3 py-2 {{ $item->status ? 'bg-red-600' : 'bg-green-600' }} text-white rounded text-xs font-medium">
                        {{ $item->status ? '❌ Desactivar' : '✅ Activar' }}
                    </button>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-400">
                <div class="text-4xl mb-2">📦</div>
                <p class="text-sm">No se encontraron artículos</p>
            </div>
        @endforelse
    </div>

    <!-- Paginación móvil -->
    @if($inventories->hasPages())
        <div class="mt-4 flex justify-center">
            {{ $inventories->links() }}
        </div>
    @endif

    <!-- Modal de reportes -->
    @if($showReportModal)
        <div class="app-modal fixed inset-0 bg-black bg-opacity-50 flex items-end sm:items-center justify-center z-50">
            <div class="bg-gray-800 rounded-t-lg sm:rounded-lg w-full max-w-md max-h-[90vh] overflow-y-auto">
                <div class="p-4 border-b border-gray-700">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-white">📊 Generar reporte</h3>
                        <button wire:click="closeReportModal" class="modal-action text-white bg-gray-600 hover:bg-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="p-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Tipo de reporte</label>
                        <select wire:model="reportType" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white">
                            <option value="general">General</option>
                            <option value="user">Por Usuario</option>
                            <option value="type">Por Tipo</option>
                            <option value="date">Por Fecha</option>
                        </select>
                    </div>

                    @if($reportType === 'date')
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Desde</label>
                                <input wire:model="reportDateFrom" type="date" class="w-full px-2 py-1 bg-gray-700 border border-gray-600 rounded text-white text-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Hasta</label>
                                <input wire:model="reportDateTo" type="date" class="w-full px-2 py-1 bg-gray-700 border border-gray-600 rounded text-white text-sm">
                            </div>
                        </div>
                    @endif

                    @if($reportType === 'user')
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Usuario</label>
                            <select wire:model="reportUser" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white">
                                <option value="">Todos los usuarios</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Estado</label>
                        <select wire:model="reportStatus" class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white">
                            <option value="">Todos</option>
                            <option value="1">Activos</option>
                            <option value="0">Inactivos</option>
                        </select>
                    </div>

                    <div class="flex gap-2 pt-4">
                        <button wire:click="generateReport" class="modal-action text-white flex-1 px-4 py-2 bg-green-600  rounded font-medium">
                            📄 Generar PDF
                        </button>
                        <button wire:click="closeReportModal" class="modal-action text-white flex-1 px-4 py-2 bg-gray-600  rounded font-medium">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <style>
        @media (max-width: 768px) {
            .p-2 { padding: 0.5rem; }
            .px-3 { padding-left: 0.75rem; padding-right: 0.75rem; }
            .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
            .text-sm { font-size: 0.875rem; }
            .text-xs { font-size: 0.75rem; }
        }
        
        /* Tamaño mínimo para touch targets */
        button, a, input, select {
            min-height: 44px;
        }
        
        /* Scroll suave */
        .space-y-3 > * + * {
            scroll-margin-top: 1rem;
        }
        
        /* Reducir animaciones en móvil */
        @media (prefers-reduced-motion: reduce) {
            * {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
        
        /* Soporte para notch */
        @supports (padding: max(0px)) {
            .p-2 {
                padding-left: max(0.5rem, env(safe-area-inset-left));
                padding-right: max(0.5rem, env(safe-area-inset-right));
            }
        }
    </style>

    <script>
        // Optimizaciones específicas para móvil
        document.addEventListener('DOMContentLoaded', function() {
            // Reducir animaciones en móvil
            document.body.style.setProperty('--tw-transition-duration', '0.1s');
            
            // Mejorar scroll en móvil
            document.body.style.webkitOverflowScrolling = 'touch';
            
            // Prevenir zoom en inputs en iOS
            const inputs = document.querySelectorAll('input[type="text"], input[type="search"], input[type="email"], input[type="tel"]');
            inputs.forEach(input => {
                input.style.fontSize = '16px';
            });
            
            // Mejorar experiencia táctil
            const buttons = document.querySelectorAll('button, a');
            buttons.forEach(button => {
                button.addEventListener('touchstart', function() {
                    this.style.transform = 'scale(0.95)';
                });
                
                button.addEventListener('touchend', function() {
                    this.style.transform = 'scale(1)';
                });
            });
        });
    </script>
</div>
