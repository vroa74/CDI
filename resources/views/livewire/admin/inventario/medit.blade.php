<div class="p-2 sm:p-4">
    <!-- Header móvil con información del dispositivo -->
    <div class="mb-4 p-3 bg-blue-900 rounded-lg text-white text-center">
        <h1 class="text-lg font-bold">📱 Editar Inventario Móvil</h1>
        <p class="text-xs text-blue-200">Dispositivo: {{ $deviceType }} | ID: {{ $inventoryId }}</p>
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

    @if ($errors->any())
        <div class="mb-3 p-3 bg-red-800 border border-red-600 text-red-200 rounded-lg text-sm">
            <div class="font-medium mb-1">Errores de validación:</div>
            <ul class="list-disc list-inside text-xs">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulario móvil -->
    <form wire:submit.prevent="saveInventory" class="space-y-4">
        
        <!-- Sección: Información Básica -->
        <div x-data="{ open: true }" class="bg-gray-800 rounded-lg border border-gray-700">
            <button @click="open = !open" type="button"
                class="w-full flex items-center justify-between p-3 bg-gray-700 text-white rounded-t-lg">
                <span class="font-semibold text-sm">📅 Información básica</span>
                <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            
            <div x-show="open" x-transition class="p-3 space-y-3">
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Fecha de inventario</label>
                        <input wire:model="fecha_inv" type="date" 
                            class="w-full px-2 py-2 bg-gray-700 border border-gray-600 rounded text-white text-sm">
                        @error('fecha_inv')
                            <span class="text-red-400 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Fecha</label>
                        <input wire:model="fecha" type="date" 
                            class="w-full px-2 py-2 bg-gray-700 border border-gray-600 rounded text-white text-sm">
                        @error('fecha')
                            <span class="text-red-400 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div>
                    <label class="block text-xs font-medium text-gray-300 mb-1">Dirección o ubicación</label>
                    <input wire:model="dir" type="text" placeholder="Dirección o ubicación"
                        class="w-full px-2 py-2 bg-gray-700 border border-gray-600 rounded text-white text-sm placeholder-gray-400">
                    @error('dir')
                        <span class="text-red-400 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Sección: Usuarios y Responsables -->
        <div x-data="{ open: false }" class="bg-gray-800 rounded-lg border border-gray-700">
            <button @click="open = !open" type="button"
                class="w-full flex items-center justify-between p-3 bg-gray-700 text-white rounded-t-lg">
                <span class="font-semibold text-sm">👥 Usuarios y responsables</span>
                <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            
            <div x-show="open" x-transition class="p-3 space-y-3">
                <div class="grid grid-cols-1 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Usuario</label>
                        <div class="flex gap-2">
                            <input wire:model="user" type="text" placeholder="Usuario asignado" readonly
                                class="flex-1 px-2 py-2 bg-gray-700 border border-gray-600 rounded text-white text-sm">
                            <button type="button" wire:click="openUserModal('user')"
                                class="px-3 py-2 bg-blue-600 text-white rounded text-xs font-medium">
                                🔍 Buscar
                            </button>
                        </div>
                        @error('user_id')
                            <span class="text-red-400 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Responsable</label>
                        <div class="flex gap-2">
                            <input wire:model="resguardante" type="text" placeholder="Responsable" readonly
                                class="flex-1 px-2 py-2 bg-gray-700 border border-gray-600 rounded text-white text-sm">
                            <button type="button" wire:click="openUserModal('responsible')"
                                class="px-3 py-2 bg-green-600 text-white rounded text-xs font-medium">
                                🔍 Buscar
                            </button>
                        </div>
                        @error('res_id')
                            <span class="text-red-400 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Nombre del Resguardante</label>
                        <input wire:model="resguardante_edit" type="text" placeholder="Nombre del resguardante"
                            class="w-full px-2 py-2 bg-gray-700 border border-gray-600 rounded text-white text-sm placeholder-gray-400">
                        @error('resguardante_edit')
                            <span class="text-red-400 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección: Información del Artículo -->
        <div x-data="{ open: false }" class="bg-gray-800 rounded-lg border border-gray-700">
            <button @click="open = !open" type="button"
                class="w-full flex items-center justify-between p-3 bg-gray-700 text-white rounded-t-lg">
                <span class="font-semibold text-sm">📦 Información del artículo</span>
                <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            
            <div x-show="open" x-transition class="p-3 space-y-3">
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Artículo</label>
                        <input wire:model="articulo" type="text" placeholder="Nombre del artículo"
                            class="w-full px-2 py-2 bg-gray-700 border border-gray-600 rounded text-white text-sm placeholder-gray-400">
                        @error('articulo')
                            <span class="text-red-400 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Número de inventario</label>
                        <input wire:model="ni" type="text" placeholder="NI"
                            class="w-full px-2 py-2 bg-gray-700 border border-gray-600 rounded text-white text-sm placeholder-gray-400">
                        @error('ni')
                            <span class="text-red-400 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Marca</label>
                        <input wire:model="marca" type="text" placeholder="Marca"
                            class="w-full px-2 py-2 bg-gray-700 border border-gray-600 rounded text-white text-sm placeholder-gray-400">
                        @error('marca')
                            <span class="text-red-400 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Modelo</label>
                        <input wire:model="modelo" type="text" placeholder="Modelo"
                            class="w-full px-2 py-2 bg-gray-700 border border-gray-600 rounded text-white text-sm placeholder-gray-400">
                        @error('modelo')
                            <span class="text-red-400 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Número de serie</label>
                        <input wire:model="ns" type="text" placeholder="NS"
                            class="w-full px-2 py-2 bg-gray-700 border border-gray-600 rounded text-white text-sm placeholder-gray-400">
                        @error('ns')
                            <span class="text-red-400 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Tipo</label>
                        <input wire:model="type" type="text" placeholder="Tipo"
                            class="w-full px-2 py-2 bg-gray-700 border border-gray-600 rounded text-white text-sm placeholder-gray-400">
                        @error('type')
                            <span class="text-red-400 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="flex items-center gap-2">
                    <input wire:model="is_pc" type="checkbox" id="is_pc" class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded">
                    <label for="is_pc" class="text-sm text-gray-300">Es una computadora (PC)</label>
                </div>
            </div>
        </div>

        <!-- Sección: Información Adicional -->
        <div x-data="{ open: false }" class="bg-gray-800 rounded-lg border border-gray-700">
            <button @click="open = !open" type="button"
                class="w-full flex items-center justify-between p-3 bg-gray-700 text-white rounded-t-lg">
                <span class="font-semibold text-sm">📋 Información adicional</span>
                <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            
            <div x-show="open" x-transition class="p-3 space-y-3">
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Grupo</label>
                        <input wire:model="gpo" type="text" placeholder="Grupo"
                            class="w-full px-2 py-2 bg-gray-700 border border-gray-600 rounded text-white text-sm placeholder-gray-400">
                        @error('gpo')
                            <span class="text-red-400 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">Dispositivo</label>
                        <input wire:model="disp" type="text" placeholder="Dispositivo"
                            class="w-full px-2 py-2 bg-gray-700 border border-gray-600 rounded text-white text-sm placeholder-gray-400">
                        @error('disp')
                            <span class="text-red-400 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">APA</label>
                        <input wire:model="apa" type="text" placeholder="APA"
                            class="w-full px-2 py-2 bg-gray-700 border border-gray-600 rounded text-white text-sm placeholder-gray-400">
                        @error('apa')
                            <span class="text-red-400 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-300 mb-1">AMA</label>
                        <input wire:model="ama" type="text" placeholder="AMA"
                            class="w-full px-2 py-2 bg-gray-700 border border-gray-600 rounded text-white text-sm placeholder-gray-400">
                        @error('ama')
                            <span class="text-red-400 text-xs">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                
                <div>
                    <label class="block text-xs font-medium text-gray-300 mb-1">Nombres</label>
                    <input wire:model="nombres" type="text" placeholder="Nombres"
                        class="w-full px-2 py-2 bg-gray-700 border border-gray-600 rounded text-white text-sm placeholder-gray-400">
                    @error('nombres')
                        <span class="text-red-400 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-xs font-medium text-gray-300 mb-1">Nombre Completo</label>
                    <input wire:model="fullname" type="text" placeholder="Nombre completo"
                        class="w-full px-2 py-2 bg-gray-700 border border-gray-600 rounded text-white text-sm placeholder-gray-400">
                    @error('fullname')
                        <span class="text-red-400 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Sección: Información PC (condicional) -->
        @if($is_pc)
        <div x-data="{ open: false }" class="bg-gray-800 rounded-lg border border-gray-700">
            <button @click="open = !open" type="button"
                class="w-full flex items-center justify-between p-3 bg-blue-700 text-white rounded-t-lg">
                <span class="font-semibold text-sm">💻 Información de PC</span>
                <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            
            <div x-show="open" x-transition class="p-3 space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-300 mb-1">Software Instalado</label>
                    <textarea wire:model="software_instalado" rows="3" placeholder="Software instalado en la PC"
                        class="w-full px-2 py-2 bg-gray-700 border border-gray-600 rounded text-white text-sm placeholder-gray-400"></textarea>
                    @error('software_instalado')
                        <span class="text-red-400 text-xs">{{ $message }}</span>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-xs font-medium text-gray-300 mb-1">Información PC</label>
                    <textarea wire:model="info_pc" rows="3" placeholder="Información adicional de la PC"
                        class="w-full px-2 py-2 bg-gray-700 border border-gray-600 rounded text-white text-sm placeholder-gray-400"></textarea>
                    @error('info_pc')
                        <span class="text-red-400 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>
        @endif

        <!-- Sección: Observaciones -->
        <div x-data="{ open: false }" class="bg-gray-800 rounded-lg border border-gray-700">
            <button @click="open = !open" type="button"
                class="w-full flex items-center justify-between p-3 bg-gray-700 text-white rounded-t-lg">
                <span class="font-semibold text-sm">📝 Observaciones</span>
                <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            
            <div x-show="open" x-transition class="p-3">
                <div>
                    <label class="block text-xs font-medium text-gray-300 mb-1">Observaciones</label>
                    <textarea wire:model="observaciones" rows="3" placeholder="Observaciones adicionales"
                        class="w-full px-2 py-2 bg-gray-700 border border-gray-600 rounded text-white text-sm placeholder-gray-400"></textarea>
                    @error('observaciones')
                        <span class="text-red-400 text-xs">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Estado -->
        <div class="bg-gray-800 rounded-lg border border-gray-700 p-3">
            <div class="flex items-center gap-2">
                <input wire:model="status" type="checkbox" id="status" class="w-4 h-4 text-green-600 bg-gray-700 border-gray-600 rounded">
                <label for="status" class="text-sm text-gray-300">Artículo activo</label>
            </div>
        </div>

        <!-- Botones de acción -->
        <div class="flex gap-2 pt-4">
            <button type="submit" class="flex-1 px-4 py-3 bg-green-600 text-white rounded-lg font-medium text-sm">
                💾 Guardar Cambios
            </button>
            <a href="{{ route('inventario.index') }}" class="flex-1 px-4 py-3 bg-gray-600 text-white rounded-lg font-medium text-sm text-center">
                ❌ Cancelar
            </a>
        </div>
    </form>

    <!-- Modal de selección de usuario -->
    @if($showModal)
    <div class="app-modal fixed inset-0 bg-black bg-opacity-50 flex items-end sm:items-center justify-center z-50">
        <div class="bg-gray-800 rounded-t-lg sm:rounded-lg w-full max-w-md max-h-[90vh] overflow-y-auto">
            <div class="p-4 border-b border-gray-700">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-white">{{ $modalTitle }}</h3>
                    <button wire:click="closeModal" class="modal-action text-white bg-gray-600 hover:bg-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            
            <div class="p-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Buscar Usuario</label>
                    <input wire:model.live="userSearch" type="text" placeholder="Buscar por nombre o email"
                        class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded text-white">
                </div>

                <div class="max-h-60 overflow-y-auto space-y-2">
                    @forelse($users as $user)
                        <button wire:click="selectUser({{ $user->id }}, '{{ $user->name }}')"
                            class="w-full p-3 bg-gray-700 hover:bg-gray-600 rounded text-left text-white text-sm">
                            <div class="font-medium">{{ $user->name }}</div>
                            <div class="text-gray-400 text-xs">{{ $user->email }}</div>
                        </button>
                    @empty
                        <div class="text-center py-4 text-gray-400 text-sm">
                            No se encontraron usuarios
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
    @endif

    <style>
        @media (max-width: 768px) {
            .p-2 { padding: 0.5rem; }
            .px-2 { padding-left: 0.5rem; padding-right: 0.5rem; }
            .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
            .text-sm { font-size: 0.875rem; }
            .text-xs { font-size: 0.75rem; }
        }
        
        /* Tamaño mínimo para touch targets */
        button, a, input, select, textarea {
            min-height: 44px;
        }
        
        /* Scroll suave */
        .space-y-4 > * + * {
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
            const inputs = document.querySelectorAll('input[type="text"], input[type="search"], input[type="email"], input[type="tel"], input[type="date"]');
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
